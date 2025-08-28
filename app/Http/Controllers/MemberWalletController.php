<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MemberWalletController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->input('keyword');

        // Fetch members based on defined criteria
        $members = Member::where('status', 1)
            ->where('activate_date', '!=', 0)
            ->where('active', 'yes')
            ->where('paid', 'yes')
            ->where('free', 'no')
            ->where('totalBal', '>=', 500)
            ->when($keyword, function ($query) use ($keyword) {
                return $query->where(function ($q) use ($keyword) {
                    $q->where('Name', 'like', '%' . $keyword . '%')
                        ->orWhere('show_mem_id', 'like', '%' . $keyword . '%');
                });
            })
            ->having('tot_ref', '>', 1) // Using having since tot_ref may be a calculated column
            ; // Adjust the number per page as needed

        return view('admin.wallet_balance', compact('members', 'keyword'));
    }

    public function deductAmount(Request $request)
    {
        $request->validate([
            'updID' => 'required|exists:members,show_mem_id', // Validate that the ID exists in the members table
            'dedamt_' . $request->input('updID') => 'required|numeric|min:1' // Validate the amount
        ]);

        $updID = $request->input('updID');
        $Reqamt = $request->input('dedamt_' . $updID);
        $Deduction = $Reqamt * 0.15; // 15%
        $FinalAmt = $Reqamt - $Deduction;

        // Insert withdrawal record
        DB::table('payment_withdraw')->insert([
            'memid' => $updID,
            'client_id' => '111111111',
            'request_date' => now(), // Using Carbon for current date and time
            'cur_withdraw_amt' => $Reqamt,
            'deduction' => $Deduction,
            'final_amt' => $FinalAmt,
            'status' => 'Success',
            'transaction_id' => '111111111',
            'accountname' => 'Demo',
            'mobileno' => '9999999999',
            'accountno' => '9999999999',
            'ifsccode' => '9999999999',
            'confirmt_date' => now(), // Using Carbon for current date and time
            'returnurl' => json_encode(['status' => 0, 'status_id' => 1, 'message' => 'Success'])
        ]);

        // Update member's wallet
        Member::where('show_mem_id', $updID)->increment('totwithdraw', $Reqamt);
        Member::where('show_mem_id', $updID)->decrement('totalBal', $Reqamt);

        return redirect()->back()->with('message', 'Amount deducted successfully.');
    }
}
