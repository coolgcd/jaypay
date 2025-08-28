<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class RewardIncomeController extends Controller
{
    // Show all rewards for a specific member
   public function index()
{
    $member = Auth::guard('member')->user(); // Or just Auth::user() if using default guard
    $memid = $member->show_mem_id;

  $rewardIncome = DB::table('reward_income')
    ->where('member_id', $member->show_mem_id) // ✅ This matches your schema
    ->orderByDesc('id')
    ->where('is_laps', 0)
    ->paginate(10);

    return view('member.reward_income', [
        'rewardIncome' => $rewardIncome,
        'member' => $member,
        'memid' => $memid,
    ]);
}
   
 public function memberRewardIncome($memid)
    {
         $member = DB::table('member')->where('show_mem_id', $memid)->first();
        
        if (!$member) {
            return abort(404, 'Member not found');
        }

        // Get reward income records with pagination
        $rewardIncome = DB::table('reward_income')
            ->where('member_id', $memid) // Assuming member_id exists in reward_income table
            ->where('is_laps', 0)
            ->orderBy('id', 'desc')
            ->paginate(15);
        return view('member.reward_income', compact('member', 'rewardIncome', 'memid'));
    }

// Handle withdrawal request from reward (optional logic here)
    public function withdraw(Request $request, $id)
    {
        $reward = DB::table('reward_income')->where('id', $id)->first();

        if (!$reward) {
            return back()->with('error', 'Reward not found.');
        }

        // Example: Check if there's balance to withdraw
        $available = (int)$reward->reward_cash - (int)$reward->withdrawn_amount;

        if ($available <= 0) {
            return back()->with('error', 'No available reward balance to withdraw.');
        }

        // Example: move to wallet / create payout entry (you define logic)
        // DB::table('wallet')->insert([...]);

        // Update withdrawn amount
        DB::table('reward_income')
            ->where('id', $id)
            ->update([
                'withdrawn_amount' => $reward->reward_cash,
                'status' => 1,
                'updated_at' => now(),
            ]);

        return back()->with('success', 'Reward withdrawn successfully.');
    }
}
