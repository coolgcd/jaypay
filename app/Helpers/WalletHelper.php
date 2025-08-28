<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class WalletHelper
{
    public static function getMemberEarnings($memberId)
    {
        $incomeHistory = DB::table('member_income_history')
            ->where('is_laps', 0)
            ->where('member_id', $memberId)
            ->sum('amount');

        $sponsorIncome = DB::table('sponsor_daily_income')
            ->where('is_laps', 0)
            ->where('member_id', $memberId)
            ->sum('amount');

        $binaryIncome = DB::table('binary_payouts')
            ->where('member_id', $memberId)
            ->where('status', 1)
            ->where('is_laps', 0)
            ->sum('payamt');

        $salaryIncome = DB::table('salary_income')
            ->where('credited', 1)
            ->where('is_laps', 0)
            ->where('member_id', $memberId)
            ->sum('amount');

        $rewardIncome = DB::table('reward_income')
            ->where('is_laps', 0)
            ->where('member_id', $memberId)
            ->sum('amount');

        $lapsamount = DB::table('member_laps_log')
            ->where('member_id', $memberId)
            ->sum('amount');

        $withdrawn = DB::table('withdraw_requests')
            ->where('member_id', $memberId)
            ->where('status', 'approved')
            ->sum('amount');

        $recharge = DB::table('recharge_requests')
            ->where('member_id', $memberId)
            ->where('status', 'Success')
            ->sum('amount');

        $totalIncome = $incomeHistory + $sponsorIncome + $binaryIncome + $salaryIncome + $rewardIncome;
        $balance = $totalIncome - $withdrawn - $recharge;

        DB::transaction(function () use ($memberId, $totalIncome) {
            DB::table('member')
                ->where('show_mem_id', $memberId)
                ->update(['tot_income_amt' => $totalIncome]);
        });

        return [
            'total_income' => $totalIncome,
            'total_withdrawn' => $withdrawn,
            'recharge' => $recharge,
            'balance' => $balance,
            'lapsamount' => $lapsamount,
            'sources' => [
                'Daily Income' => $incomeHistory,
                'Sponsor Income' => $sponsorIncome,
                'Matching Income' => $binaryIncome,
                'Salary Income' => $salaryIncome,
                'Reward Income' => $rewardIncome,
            ]
        ];
    }

    public static function checkAllMembersCapping($memberId)
    {
        $members = DB::table('member')
            ->where('show_mem_id', $memberId)
            ->where('is_laps', 0)
            ->get();

        foreach ($members as $member) {
            $memberId = $member->show_mem_id;

            // Use cumulative cap from tot_cpping_amt instead of recalculating from payment
            $totalCap = (int) ($member->tot_cpping_amt ?? 0);
            if ($totalCap <= 0) {
                continue;
            }

            $earnings = self::getMemberEarnings($memberId);
            $totalEarned = (float) ($earnings['total_income'] ?? 0);

            if ($totalEarned >= $totalCap) {
                DB::transaction(function () use ($memberId) {
                    DB::table('member')
                        ->where('show_mem_id', $memberId)
                        ->update(['is_laps' => 1]);

                    DB::table('member_binary')
                        ->where('memid', $memberId)
                        ->update(['is_laps' => 1]);

                    DB::table('member_income_history')
                        ->where('member_id', $memberId)
                        ->update(['is_laps' => 1]);
                });
            }
        }
    }

    public static function checkSingleMemberCapping($memberId)
    {
        $member = DB::table('member')
            ->where('show_mem_id', $memberId)
            ->where('is_laps', 0)
            ->select('show_mem_id', 'tot_income_amt', 'tot_cpping_amt')
            ->first();

        if (!$member) {
            return false; // Member not found or already lapsed
        }

        $totalIncome = (float) ($member->tot_income_amt ?? 0);
        $totalCap = (float) ($member->tot_cpping_amt ?? 0);

        if ($totalIncome >= $totalCap && $totalCap > 0) {
            DB::transaction(function () use ($memberId) {
                DB::table('member')
                    ->where('show_mem_id', $memberId)
                    ->update(['is_laps' => 1]);

                DB::table('member_binary')
                    ->where('memid', $memberId)
                    ->update(['is_laps' => 1]);

                DB::table('member_daily_income')
                    ->where('member_id', $memberId)
                    ->update(['is_laps' => 1]);
            });

            return true; // Lapsed
        }

        return false; // Not yet lapsed
    }

    public static function storeLapsAmountLog($memberId, $lapsAmount, $reason = 'Cap limit reached', $matchingPairs = NULL)
    {
        try {
            $exists = DB::table('member_laps_log')
                ->where('member_id', $memberId)
                ->where('matching_pair', $matchingPairs)
                ->exists();

            if ($exists) {
                return;
            }

            DB::table('member_laps_log')->insert([
                'member_id' => $memberId,
                'amount'    => $lapsAmount,
                'reason'    => $reason,
                'matching_pair' => $matchingPairs,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {
            \Log::error("Failed to log laps amount for member {$memberId}: " . $e->getMessage());
        }
    }

    public function getLeftMembersRecursive($parentId = null, &$ltot = '')
    {
        $query = DB::table('member_binary');

        if (is_null($parentId)) {
            $query->whereNull('uplineid');
        } else {
            $query->where('uplineid', $parentId);
        }

        $results = $query->get();

        foreach ($results as $row) {
            $memberId = $row->memid;

            // Recurse down the tree
            $this->getLeftMembersRecursive($memberId, $ltot);

            // Append the member ID to the list
            $ltot .= ',' . $memberId;
        }
    }
}
