<?php

namespace App\Http\Controllers;
    use Illuminate\Support\Facades\Hash;
    use App\Models\User;
use Illuminate\Http\Request;

class changePassword extends Controller
{

    public function index(){

        return view('admin.change_password');

    }

    public function changePassword(Request $request)
    {
        // Validate the request data
        $request->validate([
            'oldpass' => 'required',
            'newpass' => 'required|min:6',
        ]);

        // Get the currently authenticated user
        $user = User::where('user_fullname', session('admin_name'))->first();

        // Check if the old password matches
        if ($user && Hash::check($request->oldpass, $user->user_password)) {
            // Update the password
            $user->update([
                'user_password' => Hash::make($request->newpass),
            ]);

            return redirect()->back()->with('success', 'Password changed successfully.');
        } else {
            return redirect()->back()->with('error', 'Invalid old password.');
        }
    }

}
