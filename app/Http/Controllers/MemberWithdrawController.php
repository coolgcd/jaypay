<?php

namespace App\Http\Controllers;
use App\Helpers\WalletHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Helpers\PaymentLogHelper;

use Illuminate\Http\Request;

class MemberWithdrawController extends Controller
{
    public function index()
{
    $member = Auth::guard('member')->user();

    $wallet = WalletHelper::getMemberEarnings($member->show_mem_id);

    $withdrawals = DB::table('withdraw_requests')
        ->where('member_id', $member->show_mem_id)
        ->orderByDesc('id')
        ->get();

       

    return view('member.withdraw.index', compact('wallet', 'withdrawals'));
}


public function requestWithdraw(Request $request)
{
    $member = Auth::guard('member')->user();
    $memberId = $member->show_mem_id;

    // Check if member has bank details
    $hasBankDetails = \App\Models\MemberBankDetail::where('member_id', $memberId)->exists();

    if (!$hasBankDetails) {
        return back()->withErrors([
            'msg' => 'You must submit your bank details before requesting a withdrawal.'
        ]);
    }

    // Validate the amount
    $request->validate([
        'amount' => 'required|numeric|min:300',
    ]);

    // Check pending withdrawal
    $pendingExists = DB::table('withdraw_requests')
        ->where('member_id', $memberId)
        ->where('status', 'pending')
        ->exists();

    if ($pendingExists) {
        return back()->withErrors([
            'msg' => 'You already have a pending withdrawal request. Please wait for it to be processed before submitting another.'
        ]);
    }

    // Get wallet balance
    $wallet = WalletHelper::getMemberEarnings($memberId);

    if ($request->amount > $wallet['balance']) {
        return back()->withErrors(['amount' => 'Insufficient balance.']);
    }

    $amount = $request->amount;
    $charge = round($amount * 0.10, 2); // 10% deduction
    $finalAmount = $amount - $charge;

    DB::table('withdraw_requests')->insert([
        'member_id'     => $memberId,
        'amount'        => $amount,
        'charge'        => $charge,
        'final_amount'  => $finalAmount,
        'method'        => 'bank',
        'status'        => 'pending',
        'requested_at'  => now(),
        'created_at'    => now(),
        'updated_at'    => now(),
    ]);

    return back()->with('success', 'Your withdrawal request has been submitted successfully.');
}


// public function requestWithdraw(Request $request)
// {
//     $request->validate([
//         'amount' => 'required|numeric|min:300',
//     ]);

//     $memberId = Auth::guard('member')->user()->show_mem_id;

//     // Check pending withdrawal
//     $pendingExists = DB::table('withdraw_requests')
//         ->where('member_id', $memberId)
//         ->where('status', 'pending')
//         ->exists();

//     if ($pendingExists) {
//         return back()->withErrors([
//             'msg' => 'You already have a pending withdrawal request. Please wait for it to be processed before submitting another.'
//         ]);

//     }

    

//     // Get wallet balance
//     $wallet = WalletHelper::getMemberEarnings($memberId);

//     if ($request->amount > $wallet['balance']) {
//         return back()->withErrors(['amount' => 'Insufficient balance.']);
//     }

//     $amount = $request->amount;
//     $charge = round($amount * 0.10, 2); // 10% deduction
//     $finalAmount = $amount - $charge;

//     DB::table('withdraw_requests')->insert([
//         'member_id'     => $memberId,
//         'amount'        => $amount,
//         'charge'        => $charge,
//         'final_amount'  => $finalAmount,
//         'method'        => 'bank',
//         'status'        => 'pending',
//         'requested_at'  => now(),
//         'created_at'    => now(),
//         'updated_at'    => now(),
//     ]);

  
//     return back()->with('success', 'Your withdrawal request has been submitted successfully.');
// }




}
