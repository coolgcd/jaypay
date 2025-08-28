<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MemberPayment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Helpers\PaymentLogHelper;


class MemberPaymentController extends Controller
{
    
    public function create()
    {
        return view('member.payments.form');
    }

    public function store(Request $request)
    {
        // $request->validate([
        //    'package_amount' => 'required|numeric|exists:manage_pv,pv_amount',


        //     'quantity' => 'required|integer|min:1|max:10',
        //     'screenshot' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        // ]);

        $memberId = Auth::guard('member')->user()->show_mem_id;
        $screenshotPath = $request->file('screenshot')->store('member-payments', 'public');

        MemberPayment::create([
            'member_id' => $memberId,
            'package_amount' => $request->package_amount,
            'quantity' => $request->quantity,
            'total_amount' => $request->package_amount * $request->quantity,
            'screenshot_path' => $screenshotPath,
        ]);

        

        return redirect()->route('member.payment.history')->with('success', 'Payment submitted successfully.');
    }

    public function history()
    {
        $payments = MemberPayment::where('member_id', Auth::guard('member')->user()->show_mem_id)->latest()->get();
        return view('member.payments.history', compact('payments'));
    }
}
