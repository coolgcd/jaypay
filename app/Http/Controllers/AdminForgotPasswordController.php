<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class AdminForgotPasswordController extends Controller
{
    public function showRequestForm()
    {
        return view('auth.forgot-password', ['isAdmin' => true]);
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $admin = DB::table('admins')->where('email', $request->email)->first();

        if (!$admin) {
            return back()->withErrors(['Invalid email address.']);
        }

        $token = Str::random(64);
        $expires = Carbon::now()->addHour();

        // Clean up old tokens for this email
        DB::table('admin_password_resets')->where('email', $request->email)->delete();

        DB::table('admin_password_resets')->insert([
            'email' => $request->email,
            'token' => $token,
            'expires_at' => $expires,
            'created_at' => now(),
        ]);

        $resetLink = url('/admin/reset-password?token=' . $token);

        Mail::raw("Hi $admin->name,\n\nClick the link to reset your password:\n$resetLink\n\nThis link will expire in 1 hour.", function ($message) use ($admin) {
            $message->to($admin->email)
                    ->subject('Admin Password Reset Request');
        });

        return back()->with('status', 'Reset link has been sent to your email.');
    }

    public function showResetForm(Request $request)
    {
        $token = $request->query('token');
        if (!$token) {
            abort(404);
        }

        return view('auth.reset-password', ['token' => $token, 'isAdmin' => true]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'password' => 'required|confirmed|min:6',
        ]);

        $record = DB::table('admin_password_resets')->where('token', $request->token)->first();

        if (!$record || Carbon::parse($record->expires_at)->isPast()) {
            return back()->withErrors(['Invalid or expired token.']);
        }

        DB::table('admins')->where('email', $record->email)->update([
            'password' => Hash::make($request->password),
        ]);

        DB::table('admin_password_resets')->where('token', $request->token)->delete();

        return redirect('/admin/login')->with('status', 'Password updated successfully.');
    }
}
