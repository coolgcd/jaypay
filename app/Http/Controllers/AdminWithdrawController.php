<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Helpers\PaymentLogHelper;

class AdminWithdrawController extends Controller
{
    //

public function index()
{
    $requests = DB::table('withdraw_requests')
        ->leftJoin('member', 'withdraw_requests.member_id', '=', 'member.show_mem_id')
        ->select(
            'withdraw_requests.*',
            'member.name as member_name'
        )
        ->orderByDesc('withdraw_requests.id')
        ->get();

    return view('admin.withdraw.index', compact('requests'));
}


// public function approve($id)
// {
//     DB::table('withdraw_requests')->where('id', $id)->update([
//         'status' => 'approved',
//         'approved_at' => now(),
//         'updated_at' => now(),
//     ]);


//      PaymentLogHelper::log(
//                     type: 'income',
//                     member_id: $memberId,
//                     sub_type: 'withdraw',
//                     amount: $amount,
//                     direction: 'debit',
//                     source: 'member_withdrawal',
//                     description: "Withdrawal ₹{$amount} requested by member {$memberId}",
//                     remarks: "Withdrawal request"
//                 );

//     return back()->with('success', 'Withdrawal approved.');
// }
public function update($id)
{

   
    
    // Fetch the withdrawal record
    $withdrawal = DB::table('withdraw_requests')->where('id', $id)->first();

    if (!$withdrawal) {
        return back()->withErrors(['msg' => 'Withdrawal request not found.']);
    }

    // Update status
    DB::table('withdraw_requests')->where('id', $id)->update([
        'status' => 'approved',
        'approved_at' => now(),
        'updated_at' => now(),
    ]);

    // Log the debit in payment_logs
    $logResponse = PaymentLogHelper::log(
        type: 'withdrawal',
        member_id: $withdrawal->member_id,
        sub_type: 'withdraw',
        amount: $withdrawal->final_amount,
        direction: 'debit',
        source: 'member_withdrawal',
        description: "Withdrawal ₹{$withdrawal->final_amount} approved for member {$withdrawal->member_id}",
        remarks: "Withdrawal approved"
    );

    // Debug the log response
    

    return back()->with('success', 'Withdrawal approved.');
}


public function reject($id)
{
    DB::table('withdraw_requests')->where('id', $id)->update([
        'status' => 'rejected',
        'updated_at' => now(),
    ]);

    return back()->with('success', 'Withdrawal rejected.');
}
public function approve($id)
{
   
    DB::table('withdraw_requests')->where('id', $id)->update([
        'status' => 'approved',
        'approved_at' => now(),
        'updated_at' => now(),
    ]);

    return back()->with('success', 'Withdrawal approved.');
}


}
