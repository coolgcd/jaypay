<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;



use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Helpers\WalletHelper;

class CronController extends Controller
{

   
    public function calculatedailyincome()
    {
        
        
        $today = Carbon::today();
        $dayOfWeek = $today->format('l'); 
        if (in_array($dayOfWeek, ['Saturday', 'Sunday'])) {
            return "Skipped: Today is {$dayOfWeek}, daily income not processed.";
            }
        $currentTimestamp = Carbon::now()->timestamp;
        $todayTimestamp = Carbon::today()->addMinutes(30)->timestamp;
 

        $eligibleMembers = DB::table('member_daily_income')
            ->where('start_date', '<=', $currentTimestamp)
            ->where('is_laps', 0)
            ->where(function ($q) use ($currentTimestamp) {
                $q->where('end_date', '>=', $currentTimestamp)
                    ->orWhereNull('end_date');
            })
            ->whereColumn('total_received', '<', 'amount')
            ->get();

        $processedCount = 0;

        foreach ($eligibleMembers as $entry) {
        $capresponce = WalletHelper::checkSingleMemberCapping($entry->member_id);

        $member = DB::table('member')->where('show_mem_id', $entry->member_id)->first();
        if (!$member || $member->is_laps == 1) {
            continue; // Skip lapsed members
        }

            $packageAmount = $member->payment ?? 0;

            $isWorking = DB::table('member')
                ->where('sponsorid', $member->show_mem_id)
                ->where('status', 1)
                ->exists();

            $capAmount = $isWorking ? ($packageAmount * 3) : ($packageAmount * 2);
            $earnings = WalletHelper::getMemberEarnings($member->show_mem_id);
            $totalEarned = $earnings['total_income'];

            $remainingCap = $capAmount - $totalEarned;
            if ($remainingCap <= 0) {
                continue;
            }

            $dailyIncome = round($entry->amount * 0.005, 2); // 0.5%
            $remainingDaily = $entry->amount - $entry->total_received;

            if ($dailyIncome > $remainingDaily) {
                $dailyIncome = $remainingDaily;
            }

            if ($dailyIncome > $remainingCap) {
                $dailyIncome = $remainingCap;
            }

            if ($dailyIncome <= 0) {
                continue;
            }

            // 🔒 Check if already given today
            $existingEntry = DB::table('member_income_history')
                ->where('member_id', $entry->member_id)
                ->where('date', $todayTimestamp)
                ->first();

            if ($existingEntry) {
                continue;
            }

            try {
                // Insert today's income
                DB::table('member_income_history')->insert([
                    'member_id'   => $entry->member_id,
                    'amount'      => $dailyIncome,
                    'date'        => $todayTimestamp,
                    'created_at'  => $currentTimestamp,
                    'updated_at'  => $currentTimestamp,
                ]);

                \App\Helpers\PaymentLogHelper::log(
                    type: 'earning',
                    member_id: $entry->member_id,
                    sub_type: 'daily_income',
                    amount: $dailyIncome,
                    direction: 'debit',
                    source: 'system',
                    description: "Daily ROI ₹{$dailyIncome} credited to {$entry->member_id}",
                    remarks: "Auto-credited as part of daily ROI cycle"
                );


                DB::table('member_daily_income')
                    ->where('id', $entry->id)
                    ->update([
                        'total_received' => $entry->total_received + $dailyIncome,
                        'updated_at'     => $currentTimestamp,
                    ]);

                $processedCount++;
            } catch (\Exception $e) {
                // Optional: log error
            }
        }

            echo "Reward bonus processing completed.";
    }

    public function processsponsordailyIncome()
{
    $today = Carbon::today();
    $dayOfWeek = $today->format('l');

    if (in_array($dayOfWeek, ['Saturday', 'Sunday'])) {
        return "Skipped: Today is {$dayOfWeek}, daily income not processed.";
    }

    $now = Carbon::now();
    $todayDate = $now->toDateString();

    $records = DB::table('direct_payment_tbl')
        ->where('start_date', '<=', $now->timestamp)
        ->where('end_date', '>=', $now->timestamp)
        ->get();

    foreach ($records as $record) {
        $sponsorId = $record->member_id;
        $fromId = $record->from_id;
        $totalReceived = $record->total_received;
        $cap = $record->amount;
        $perDay = $cap * 0.005;
        WalletHelper::checkSingleMemberCapping($sponsorId);
        $member = DB::table('member')->where('show_mem_id', $sponsorId)->first();
        if (!$member) {
            continue;
        }

        $packageAmount = $member->payment ?? 0;

        $isWorking = DB::table('member')
            ->where('sponsorid', $sponsorId)
            ->where('status', 1)
            ->exists();

        $capAmount = $isWorking ? ($packageAmount * 3) : ($packageAmount * 2);
        $earnings = WalletHelper::getMemberEarnings($sponsorId);
        $totalEarned = $earnings['total_income'];
        $remainingCap = $capAmount - $totalEarned;

        // if ($remainingCap <= 0 || $totalReceived >= $cap) {
        //     continue;
        // }

        // Adjust perDay based on direct cap limit
        if ($totalReceived + $perDay > $cap) {
            $perDay = $cap - $totalReceived;
        }

        // Split into credited and lapsed amounts
        $lapAmount = 0;
        $creditedAmount = $perDay;

        if ($perDay > $remainingCap) {
            $lapAmount = $perDay - $remainingCap;
            $creditedAmount = $remainingCap;
        }

        if ($creditedAmount <= 0) {
            // Fully lapsed — log it
            if ($perDay > 0) {
                WalletHelper::storeLapsAmountLog($sponsorId, $perDay, 'Full lapse from sponsor daily income');
            }
            continue;
        }

        // Check duplicate for today
        $alreadyExists = DB::table('sponsor_daily_income')
            ->where('member_id', $sponsorId)
            ->where('from_id', $fromId)
            ->whereDate('created_at', $todayDate)
            ->exists();

        if ($alreadyExists) {
            continue;
        }

        // Insert income record
        DB::table('sponsor_daily_income')->insert([
            'member_id'  => $sponsorId,
            'from_id'    => $fromId,
            'amount'     => $creditedAmount,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        \App\Helpers\PaymentLogHelper::log(
            type: 'income',
            member_id: $sponsorId,
            sub_type: 'sponsor_daily_income',
            amount: $creditedAmount,
            direction: 'debit',
            source: 'sponsor_roi',
            description: "Sponsor ROI ₹{$creditedAmount} credited to member {$sponsorId} (from {$fromId})",
            remarks: "Auto-credited daily sponsor income"
        );

        // Update total_received
        DB::table('direct_payment_tbl')
            ->where('id', $record->id)
            ->update([
                'total_received' => $totalReceived + $creditedAmount,
                'updated_at'     => $now->timestamp,
            ]);

        // Log any lapsed portion
        if ($lapAmount > 0) {
            WalletHelper::storeLapsAmountLog($sponsorId, $lapAmount, 'Partial lapse from sponsor daily income');
        }
    }

    echo "Reward bonus processing completed.";
}



    protected $salarySlabs = [
        50000     => 500,
        150000    => 1000,
        400000    => 2000,
        900000    => 4000,
        1900000   => 8000,
        4300000   => 20000,
        9300000   => 40000,
        19300000  => 100000,
    ];

    public function processsalaryincome()
{
    $members = DB::table('member')->get();

    // Mark expired salary incomes as credited
    DB::table('salary_income')
        ->where('credited', 0)
        ->where('to_date', '<=', now()->timestamp)
        ->update(['credited' => 1]);

    foreach ($members as $mem) {
        $payouts = DB::table('binary_payouts')
            ->where('member_id', $mem->show_mem_id)
            ->where('status', 1)
            ->where('created_at', '>=', $mem->activate_date)
            ->orderBy('created_at')
            ->get();
WalletHelper::checkSingleMemberCapping($mem->show_mem_id);
        if ($payouts->isEmpty()) {
            continue;
        }

        $existingSlabs = DB::table('salary_income')
            ->where('member_id', $mem->show_mem_id)
            ->pluck('matching_income')
            ->toArray();

        $cumulativeMatching = 0;
        $processedSlabs = [];

        foreach ($payouts as $payout) {
            $cumulativeMatching += (int)$payout->tot_matching;

            foreach ($this->salarySlabs as $slabMatch => $salaryAmount) {
                if (
                    $cumulativeMatching >= $slabMatch &&
                    !in_array($slabMatch, $processedSlabs) &&
                    !in_array($slabMatch, $existingSlabs)
                ) {
                    // Cap logic
                    $packageAmount = $mem->payment ?? 0;
                    $isWorking = DB::table('member')
                        ->where('sponsorid', $mem->show_mem_id)
                        ->where('status', 1)
                        ->exists();

                    $capAmount = $isWorking ? $packageAmount * 3 : $packageAmount * 2;
                    $totalEarned = WalletHelper::getMemberEarnings($mem->show_mem_id)['total_income'];

                    $totalSalaryToPay = $salaryAmount * 6;
                    $lapAmount = 0;

                    if ($totalEarned >= $capAmount) {
                        // Fully lapsed
                        WalletHelper::storeLapsAmountLog($mem->show_mem_id, $totalSalaryToPay, "Full lapse: Salary slab ₹{$slabMatch}");
                        continue;
                    }

                    if (($totalEarned + $totalSalaryToPay) > $capAmount) {
                        $lapAmount = ($totalEarned + $totalSalaryToPay) - $capAmount;
                        $totalSalaryToPay = $capAmount - $totalEarned;
                    }

                    if ($totalSalaryToPay <= 0) {
                        if ($lapAmount > 0) {
                            WalletHelper::storeLapsAmountLog($mem->show_mem_id, $lapAmount, "Lapsed due to cap for salary slab ₹{$slabMatch}");
                        }
                        continue;
                    }

                    $monthlySalary = floor($totalSalaryToPay / 6);
                    $startDate = Carbon::parse($payout->created_at)->startOfDay();

                    for ($i = 0; $i < 6; $i++) {
                        $salaryDate = $startDate->copy()->addMonths($i);

                        $salaryIncomeId = DB::table('salary_income')->insertGetId([
                            'member_id'        => $mem->show_mem_id,
                            'amount'           => $monthlySalary,
                            'matching_income'  => $slabMatch,
                            'from_date'        => $startDate->timestamp,
                            'to_date'          => $salaryDate->timestamp,
                            'created_at'       => now()->timestamp,
                            'updated_at'       => now()->timestamp,
                        ]);

                        \App\Helpers\PaymentLogHelper::log(
                            type: 'income',
                            member_id: $mem->show_mem_id,
                            sub_type: 'salary_income',
                            amount: $monthlySalary,
                            direction: 'debit',
                            source: 'salary_bonus',
                            description: "Salary ₹{$monthlySalary} from slab ₹{$slabMatch} to member {$mem->show_mem_id}",
                            remarks: "Month " . ($i + 1)
                        );

                        DB::table('salary_income_logs')->insert([
                            'salary_income_id' => $salaryIncomeId,
                            'member_id'        => $mem->show_mem_id,
                            'amount'           => $monthlySalary,
                            'date'             => $salaryDate->timestamp,
                            'created_at'       => now()->timestamp,
                            'updated_at'       => now()->timestamp,
                        ]);
                    }

                    // Log lapse amount if any
                    if ($lapAmount > 0) {
                        WalletHelper::storeLapsAmountLog($mem->show_mem_id, $lapAmount, "Partial lapse from salary slab ₹{$slabMatch}");
                    }

                    $processedSlabs[] = $slabMatch;
                }
            }
        }
    }

    echo "Salary Income processing completed.";
}

    protected $rewardSlabs = [
        50000     => ['amount' => 1000,    'rank' => 'Bronze'],
        100000    => ['amount' => 2000,    'rank' => 'Silver'],
        500000    => ['amount' => 5000,    'rank' => 'Gold'],
        1000000   => ['amount' => 10000,   'rank' => 'Platinum'],
        2500000   => ['amount' => 25000,   'rank' => 'Ruby'],
        5000000   => ['amount' => 50000,   'rank' => 'Diamond'],
        10000000  => ['amount' => 100000,  'rank' => 'Crown'],
    ];
    
public function processrewardbonus()
{
    $members = DB::table('member_binary')
        ->where('left', '!=', '')
        ->where('right', '!=', '')
        ->where('status', 1)
        ->select('memid')
        ->get();

    foreach ($members as $member) {
        $rewardSlabs = collect($this->rewardSlabs)->sortKeys();

        $gettotmatching = DB::table('binary_payouts')
            ->where('member_id', $member->memid)
            ->sum('tot_matching');
         WalletHelper::checkSingleMemberCapping($member->memid);

        $usedPairs = DB::table('reward_income')
            ->where('member_id', $member->memid)
            ->sum('matching_pair');

        $availablePairs = (int)$gettotmatching - (int)$usedPairs;

        if ($availablePairs <= 0) {
            continue;
        }

        foreach ($rewardSlabs as $requiredPairs => $data) {
            if ($availablePairs < $requiredPairs) {
                continue;
            }

            $alreadyExists = DB::table('reward_income')
                ->where('member_id', $member->memid)
                ->where('matching_pair', $requiredPairs)
                ->exists();

            if ($alreadyExists) {
                continue;
            }

            $mem = DB::table('member')->where('show_mem_id', $member->memid)->first();
            if (!$mem) {
                continue;
            }

            $packageAmount = $mem->payment ?? 0;

            $isWorking = DB::table('member')
                ->where('sponsorid', $mem->show_mem_id)
                ->where('status', 1)
                ->exists();

            $capAmount = $isWorking ? ($packageAmount * 3) : ($packageAmount * 2);
            $totalEarned = WalletHelper::getMemberEarnings($mem->show_mem_id)['total_income'];

            $finalReward = $data['amount'];
            $lapsAmount = 0;

          if ($totalEarned >= $capAmount) {
                WalletHelper::storeLapsAmountLog(
                    $mem->show_mem_id,
                    $finalReward,
                    "Reward lapsed (Rank: {$data['rank']}, Pairs: {$requiredPairs}) due to full cap",
                    $requiredPairs
                );
                continue;
            }

            if (($totalEarned + $finalReward) > $capAmount) {
                $lapsAmount = ($totalEarned + $finalReward) - $capAmount;
                $finalReward = $capAmount - $totalEarned;
            }

           if ($finalReward <= 0) {
                if ($lapsAmount > 0) {
                    WalletHelper::storeLapsAmountLog(
                        $mem->show_mem_id,
                        $lapsAmount,
                        "Reward lapsed (Rank: {$data['rank']}, Pairs: {$requiredPairs}) due to cap",
                        $requiredPairs
                    );
                }
                continue;
            }
            // ✅ Insert reward
            DB::table('reward_income')->insert([
                'member_id'        => $member->memid,
                'matching_pair'    => $requiredPairs,
                'rank'             => $data['rank'],
                'amount'           => $finalReward,
                'reward_cash'      => $finalReward,
                'withdrawn_amount' => 0,
                'status'           => 0,
                'created_at'       => now(),
                'updated_at'       => now(),
            ]);

            \App\Helpers\PaymentLogHelper::log(
                type: 'income',
                member_id: $member->memid,
                sub_type: 'reward_income',
                amount: $finalReward,
                direction: 'debit',
                source: 'reward_bonus',
                description: "Reward ₹{$finalReward} granted to member {$member->memid} — Rank: {$data['rank']}",
                remarks: "Used {$requiredPairs} pairs, auto reward"
            );

            $rewardIncomeId = DB::getPdo()->lastInsertId();

            DB::table('reward_income_logs')->insert([
                'reward_income_id' => $rewardIncomeId,
                'member_id'        => $member->memid,
                'amount'           => $finalReward,
                'type'             => 'auto',
                'created_at'       => now(),
                'updated_at'       => now(),
            ]);

            // Log lapsed portion
            if ($lapsAmount > 0) {
                WalletHelper::storeLapsAmountLog(
                    $mem->show_mem_id,
                    $lapsAmount,
                    "Partial reward lapsed (Rank: {$data['rank']}) due to cap"
                );
            }

            // Deduct pairs and move to next eligible slab
            $availablePairs -= $requiredPairs;

            if ($availablePairs <= 0) {
                break;
            }

            echo "✅ Reward inserted for {$member->memid} — Rank: {$data['rank']} — ₹{$finalReward} — Used {$requiredPairs} pairs\n";
        }
    }

    echo "Reward bonus processing completed.";
}


}
