<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;





use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends Controller
{
    public function showRequestForm()
    {
        return view('member.auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'show_mem_id' => 'required',
            'email' => 'required|email',
        ]);

        $member = DB::table('member')
            ->where('show_mem_id', $request->show_mem_id)
            ->where('emailid', $request->email)
            ->first();

        if (!$member) {
            return back()->withErrors(['Invalid Member ID or Email.']);
        }

        // Generate token
        $token = Str::random(64);
        $expires = Carbon::now()->addHour();

        // Store in table
        DB::table('password_resets')->insert([
            'show_mem_id' => $request->show_mem_id,
            'token' => $token,
            'expires_at' => $expires,
            'created_at' => now(),
        ]);

        // Send email
        $resetLink = url('/member/reset-password?token=' . $token);

        Mail::raw("Hi $member->name,\n\nClick the link to reset your password:\n$resetLink\n\nThis link will expire in 1 hour.", function ($message) use ($member) {
            $message->to($member->emailid)
                ->subject('Password Reset Request');
        });

        return back()->with('status', 'Reset link has been sent to your email.');
    }

    public function showResetForm(Request $request)
    {
        $token = $request->query('token');
        if (!$token) {
            abort(404);
        }
        return view('member.auth.reset-password', compact('token'));
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'password' => 'required|confirmed|min:6',
        ]);

        $record = DB::table('password_resets')
            ->where('token', $request->token)
            ->first();

        if (!$record) {
            return back()->withErrors(['Invalid or expired token.']);
        }

        if (Carbon::parse($record->expires_at)->isPast()) {
            return back()->withErrors(['Token expired.']);
        }

        // Update password
        DB::table('member')
            ->where('show_mem_id', $record->show_mem_id)
            ->update([
                'password' => bcrypt($request->password),
            ]);

        // Delete token
        DB::table('password_resets')
            ->where('token', $request->token)
            ->delete();

        return redirect()->route('member.login')->with('status', 'Password updated successfully.');
    }
}
