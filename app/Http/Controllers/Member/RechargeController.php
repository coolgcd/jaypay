<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Helpers\WalletHelper;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Log;
class RechargeController extends Controller
{
    public function showForm()
    {
        return view('member.recharge.mobile');
    }

        public function submit(Request $request)
        {  
            
            $member = Auth::guard('member')->user(); 
            $username  = env('IINDIPAY_USERNAME');
            $apiToken  = env('IINDIPAY_API_TOKEN');
            $number    = $request->input('mobile_number');
            $amount    = $request->input('amount');
            $operator  = strtoupper($request->input('operator'));
            $refId     = uniqid('ref_');

        
            $wallet = WalletHelper::getMemberEarnings($member->show_mem_id);

    // Check if balance is enough
    if ($wallet['balance'] < $amount) {
        return back()->withErrors(['msg' => 'Your wallet balance is insufficient to complete this recharge.']);
    }




            $url = "https://www.iindiapay.com/webservices/api/recharge";

            // Call the API
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
    'member_id'  => $member->show_mem_id, // ✅ Added
    'number'     => $number,
    'amount'     => $amount,
    'operator'   => $operator,
    'status'     => 'pending',
    'created_at' => now(),
]);

        if ($response->successful()) {
            $data = $response->json();
            $status = $data['status'] ?? 'Error';


            Log::info('Updating recharge_requests row', [
    'where_ref_id' => $refId,
    'update_data' => [
        'status'     => $status,
        'txn_id'     => $data['txn_id'] ?? null,
        'opt_id'     => $data['opt_id'] ?? null,
        'balance'    => $data['balance'] ?? null,
        'message'    => $data['message'] ?? null,
        'updated_at' => now(),
    ]
]);

         DB::table('recharge_requests')
    ->where('ref_id', $refId)
    ->update([
        'status'     => $status,
        'txn_id'     => isset($data['txn_id']) && $data['txn_id'] !== '' ? (int)$data['txn_id'] : null,
        'opt_id'     => $data['opt_id'] ?: null,
        'balance'    => isset($data['balance']) && $data['balance'] !== '' ? (float)$data['balance'] : null,
        'message'    => $data['message'] ?: null,
        'updated_at' => now(),
    ]);

            // Redirect based on response
            if (in_array($status, ['Accepted', 'Success', 'Pending'])) {
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

    public function history()
{
    $member = Auth::guard('member')->user();

    $recharges = DB::table('recharge_requests')
        ->where('member_id', $member->show_mem_id)
        ->orderByDesc('created_at')
        ->get();

    return view('member.recharge.history', compact('recharges'));
}

}
