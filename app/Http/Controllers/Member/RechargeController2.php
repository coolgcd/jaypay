<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Helpers\WalletHelper;
use Illuminate\Support\Facades\Auth;

class RechargeController extends Controller
{
    public function showForm()
    {
        return view('member.recharge.mobile');
    }

    public function submit(Request $request)
    {
        $username  = env('IINDIPAY_USERNAME');
        $apiToken  = env('IINDIPAY_API_TOKEN');
        $number    = $request->input('mobile_number');
        $amount    = $request->input('amount');
        $operator  = strtoupper($request->input('operator'));
        $refId     = uniqid('ref_');

        // Get member + balance
        $member = Auth::guard('member')->user();
        $wallet = WalletHelper::getMemberEarnings($member->show_mem_id);

        // ❌ Block if not enough balance
        if ($amount > $wallet['balance']) {
            return back()->withErrors(['amount' => 'Insufficient balance to complete this recharge.']);
            
        }

        $url = "https://www.iindiapay.com/webservices/api/recharge";

        // ✅ Call the API
        $response = Http::get($url, [
            'username'  => $username,
            'api_token' => $apiToken,
            'number'    => $number,
            'amount'    => $amount,
            'operator'  => $operator,
            'ref_id'    => $refId,
        ]);

        // Save initial request
        DB::table('recharge_requests')->insert([
            'ref_id'     => $refId,
            'member_id'  => $member->show_mem_id,
            'number'     => $number,
            'amount'     => $amount,
            'operator'   => $operator,
            'status'     => 'pending',
            'created_at' => now(),
        ]);

        // ✅ If API worked
        if ($response->successful()) {
            $data = $response->json();
            $status = $data['status'] ?? 'Error';

            DB::table('recharge_requests')
                ->where('ref_id', $refId)
                ->update([
                    'status'     => $status,
                    'txn_id'     => $data['txn_id'] ?? null,
                    'opt_id'     => $data['opt_id'] ?? null,
                    'balance'    => $data['balance'] ?? null,
                    'message'    => $data['message'] ?? null,
                    'updated_at' => now(),
                ]);

            // ✅ Insert into withdraw_requests if recharge was successful
            if (in_array($status, ['Accepted', 'Success', 'Pending'])) {
                DB::table('withdraw_requests')->insert([
                    'member_id'     => $member->show_mem_id,
                    'amount'        => $amount,
                    'method'        => 'Recharge',
                    'status'        => 'approved',
                    'requested_at'  => now(),
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);

                return redirect()->route('member.recharge.success')->with('recharge_data', $data);
            } else {
                return view('member.recharge.error', ['data' => $data]);
            }
        }

        return back()->withErrors(['msg' => 'Recharge API failed. Please try again later.']);
    }

    public function success()
    {
        if (!session()->has('recharge_data')) {
            return redirect()->route('member.recharge.mobile');
        }

        $data = session('recharge_data');
        return view('member.recharge.success', compact('data'));
    }
}
