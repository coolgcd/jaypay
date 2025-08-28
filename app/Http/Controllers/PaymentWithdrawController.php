<?php
namespace App\Http\Controllers;
use App\Models\User; // Correct namespace for User model

use Illuminate\Http\Request;
use App\Models\PaymentWithdraw; // Assume you have a model for payment_withdraw
use App\Models\Member; // Assume you have a model for member
use Illuminate\Support\Facades\DB; // Import the DB facade
use Illuminate\Support\Facades\Auth; // <-- Add this line

class PaymentWithdrawController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->input('keyword');
        $query = PaymentWithdraw::query();

        if ($keyword) {
            $query->where('memid', $keyword);
        }

        $withdrawals = $query->orderBy('calctime', 'desc');
        return view('admin.recognition_income', compact('withdrawals', 'keyword'));
    }

    public function delete($id)
    {
        $withdrawal = PaymentWithdraw::findOrFail(base64_decode($id));

        // Fetch the member details
        $member = Member::find($withdrawal->memid);
        $message = $withdrawal->status === 'Low balance' ? 'Network Issue' : $withdrawal->status;

        // Send SMS (implement your SMS function)
        if ($member->mobileno) {
            $mmessage = "Dear {$member->name}, Your payment of {$withdrawal->final_amt} has failed. Reason: {$message}. Kindly check your bank account details. FlipDeal";
            $this->sendSMS($member->mobileno, $mmessage);
        }

        $withdrawal->delete();

        return redirect()->route('payment.withdraw.index')->with('success', 'Record deleted successfully');
    }

    public function reportUpdate($id)
    {
        $withdrawal = PaymentWithdraw::findOrFail(base64_decode($id));
        $response = $this->checkAuthentication($withdrawal->client_id);

        if ($response['status'] == 1) {
            $withdrawal->update(['status' => 'Success', 'transaction_id' => $response['utr']]);
        } elseif ($response['status'] == 2) {
            $withdrawal->update(['status' => 'Failure', 'transaction_id' => $response['utr']]);
        } elseif ($response['status'] == 3) {
            $withdrawal->update(['status' => 'Pending', 'transaction_id' => $response['utr']]);
        } elseif ($response['status'] == 4) {
            $withdrawal->update(['status' => 'Refund', 'transaction_id' => $response['utr']]);
        }

        return redirect()->route('payment.withdraw.index');
    }

    private function sendSMS($mobile, $message)
    {
        // Your SMS sending logic here
    }

    private function checkAuthentication($clientId)
    {
        $url = 'https://api.pay2all.in/v1/payment/status';
        $key = "your-api-key-here";
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $key,
            'Accept' => 'application/json',
        ])->post($url, [
            'client_id' => $clientId,
        ]);

        return $response->json();
    }

    public function list(Request $request)
    {
        $keyword = $request->input('keyword');

        $query = PaymentWithdraw::query();

        if ($keyword) {
            $query->where('memid', $keyword);
        }

        $withdraws = $query->orderBy('request_date', 'desc')->paginate(100);

        return view('admin.payment_withdraw', compact('withdraws'));
    }

    public function pending(Request $request)
    {
        // Handle search by memid or display all
        $keyword = $request->input('keyword');
        $withdrawals = PaymentWithdraw::where('status', '!=', 'Success')
            ->when($keyword, function($query, $keyword) {
                return $query->where('memid', $keyword);
            })
            ->orderByDesc('request_date')
            ->paginate(10);

        return view('withdraw.index', compact('withdrawals', 'keyword'));
    }
    public function currentBalance(Request $request)
    {
        $keyword = $request->input('keyword');

        $members = Member::where('activate_date', '!=', 0)
            ->when($keyword, function ($query) use ($keyword) {
                return $query->where('mem_id', $keyword);
            })
            ->get();

        $results = [];
        $totalBalance = 0;

        foreach ($members as $member) {
            // Calculate incomes and withdrawals using relationships
            // $singleIncome = $member->monthIncome()->sum('totalamtreceived') ?? 0;
            // $levelIncome = $member->levelIncome()->sum('rec_amt') ?? 0;
            // $withdraw = $member->withdrawals()->sum('cur_withdraw_amt') ?? 0;

            // Calculate total balance
            // $balance = ($singleIncome + $levelIncome) - $withdraw;
            // $totalBalance += $balance;

            $results[] = [
                'mem_id' => $member->mem_id,
                'name' => $member->name,
                // 'level_income' => $levelIncome,
                // 'single_income' => $singleIncome,
                // 'withdraw' => $withdraw,
                // 'balance' => $balance,
            ];
        }

        return view('admin.balance_list', [
            'results' => $results,
            'totalBalance' => $totalBalance
        ]);
    }

    public function paymentwithdraw()
    {
        $user = User::where('user_fullname', session('admin_name'))->first();
        return view('admin.payment_withdraw_setting', compact('user'));
    }
    public function levalinfo()
{
    $user = Auth::user(); // Fetch authenticated user
    $totalDeduction = 100; // Example value, replace with actual calculation logic

    return view('admin.leval_info', compact('user', 'totalDeduction'));
}

    public function update(Request $request)
    {
        $request->validate(['std_id' => 'required']);

        $cur_price = $request->std_id == 0 ? 1 : 0;

        User::where('user_fullname', session('admin_name'))->update(['cur_price' => $cur_price]);

        return redirect()->route('withdraw.index')->with('msg', 'Payment Withdraw Status updated successfully.');
    }

    public function updateUserLevels(Request $request)
{
    // Validate request
    $validated = $request->validate([
        'lev3' => 'nullable|boolean',
        'lev4' => 'nullable|boolean',
        'lev5' => 'nullable|boolean',
        'lev6' => 'nullable|boolean',
        'lev7' => 'nullable|boolean',
        'lev8' => 'nullable|boolean',
        'lev9' => 'nullable|boolean',
        'lev10' => 'nullable|boolean',
        'lev11' => 'nullable|boolean',
        'lev12' => 'nullable|boolean',
        'lev13' => 'nullable|boolean',
        'otpreq' => 'nullable|boolean',
        'sendpnd' => 'nullable|boolean',
    ]);

    // Retrieve user based on session admin name
    $user = User::where('user_fullname', session('admin_name'))->first();

    if ($user) {
        // Update user levels
        $user->update($validated);

        return redirect()->back()->with('success', 'User levels updated successfully.');
    }

    return redirect()->back()->with('error', 'User not found.');
}

public function showUpdateLevelsForm()
{
    $totalDeduction = DB::table('mem_level_income_laps')->sum('rec_amt');
    $user = User::where('user_fullname', session('admin_name'))->first();

    return view('user.update-levels', compact('totalDeduction', 'user'));
}
}
