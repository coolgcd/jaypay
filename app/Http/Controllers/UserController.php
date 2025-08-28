<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserController extends Controller
{
    public function showChangePasswordForm()
    {
        return view('admin.sitemanagment.chpassword');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'oldpass' => 'required|string',
            'newpass' => 'required|string|min:6|confirmed', // Ensure the new password is confirmed
        ]);

        $user = User::where('user_fullname', session('admin_name'))->first();

        if ($user && Hash::check($request->oldpass, $user->user_password)) {
            $user->user_password = Hash::make($request->newpass);
            $user->save();
            return redirect()->route('password.change')->with('success', 'Password changed successfully.');
        }

        return redirect()->route('password.change')->with('error', 'Invalid Old Password.');
    }
}
