<?php

// app/Http/Controllers/Auth/MemberLoginController.php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class MemberLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.member-login');
    }

public function login(Request $request)
{
    $credentials = $request->only('show_mem_id', 'password');

    if (Auth::guard('member')->attempt($credentials)) {
        return redirect()->intended('/member/dashboard');
    }

    return redirect()->back()->withErrors([
        'error' => 'Invalid Credentials',
    ]);
}




    public function logout()
    {
        Auth::guard('member')->logout();
        return redirect('/member/login');
    }
}
