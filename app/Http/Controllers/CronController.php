<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Helpers\WalletHelper;
use Illuminate\Support\Facades\Log;

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
// echo date('Y-m-d H:i:s', $todayTimestamp) ;die;
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

            // Use cumulative cap model: remainingCap = tot_cpping_amt - total_income
            $earnings = WalletHelper::getMemberEarnings($member->show_mem_id);
            $totalEarned = $earnings['total_income'];
            $totalCap = (int) DB::table('member')
                ->where('show_mem_id', $member->show_mem_id)
                ->value('tot_cpping_amt');
            $remainingCap = max($totalCap - $totalEarned, 0);

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
            Log::info("Processing entry {$entry->id}: amount={$entry->amount}, total_received={$entry->total_received}, dailyIncome={$dailyIncome}, remainingDaily={$remainingDaily}, remainingCap={$remainingCap}");

            $existingEntry = DB::table('member_income_history')
                ->where('member_id', $entry->member_id)
                ->where('member_daily_income_id', $entry->id)
                ->where('date', $todayTimestamp)
                ->first();

            if ($existingEntry) {
                continue;
            }
            Log::info("Row {$entry->id}: dailyIncome={$dailyIncome}, remainingCap={$remainingCap}, alreadyCreditedToday=" . ($existingEntry ? 1 : 0));

            try {
                // Insert today's income
                DB::table('member_income_history')->insert([
                    'member_id'   => $entry->member_id,
                    'amount'      => $dailyIncome,
                    'date'        => $todayTimestamp,
                    'member_daily_income_id' => $entry->id,
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
                    description: "Daily ROI â‚¹{$dailyIncome} credited to {$entry->member_id}",
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

        echo "Daily income processing completed.";
    }
//     public function calculatedailyincome()
// {
//     $today = Carbon::today();
//     $dayOfWeek = $today->format('l');
//     if (in_array($dayOfWeek, ['Saturday', 'Sunday'])) {
//         return "Skipped: Today is {$dayOfWeek}, daily income not processed.";
//     }

//     $currentTimestamp = Carbon::now()->timestamp;
//     $todayTimestamp = Carbon::today()->addMinutes(30)->timestamp;

//     // Get all eligible entries and group by member
//     $eligibleEntries = DB::table('member_daily_income')
//         ->where('start_date', '<=', $currentTimestamp)
//         ->where('is_laps', 0)
//         ->where(function ($q) use ($currentTimestamp) {
//             $q->where('end_date', '>=', $currentTimestamp)
//                 ->orWhereNull('end_date');
//         })
//         ->whereColumn('total_received', '<', 'amount')
//         ->get()
//         ->groupBy('member_id'); // ✅ Groups ALL topups per member

//     $processedCount = 0;

//     foreach ($eligibleEntries as $memberId => $memberEntries) {
//         // ✅ Check if member got ANY income today (prevents duplicates)
//         $alreadyPaidToday = DB::table('member_income_history')
//             ->where('member_id', $memberId)
//             ->whereDate('date', Carbon::today())
//             ->exists();

//         if ($alreadyPaidToday) {
//             continue; // Skip entire member
//         }

//         $member = DB::table('member')->where('show_mem_id', $memberId)->first();
//         if (!$member || $member->is_laps == 1) {
//             continue;
//         }

//         // Check member capping
//         $earnings = WalletHelper::getMemberEarnings($member->show_mem_id);
//         $totalEarned = $earnings['total_income'];
//         $totalCap = (int) DB::table('member')
//             ->where('show_mem_id', $member->show_mem_id)
//             ->value('tot_cpping_amt');
//         $remainingCap = max($totalCap - $totalEarned, 0);

//         if ($remainingCap <= 0) {
//             continue;
//         }

//         // ✅ Calculate income from ALL topups (regardless of amounts)
//         $totalDailyIncome = 0;
//         $entriesToUpdate = [];

//         foreach ($memberEntries as $entry) {
//             // Each topup contributes its 0.5% daily ROI
//             $dailyIncome = round($entry->amount * 0.005, 2); // Works for ANY amount
//             $remainingDaily = $entry->amount - $entry->total_received;

//             if ($dailyIncome > $remainingDaily) {
//                 $dailyIncome = $remainingDaily;
//             }

//             if ($dailyIncome > 0) {
//                 $totalDailyIncome += $dailyIncome; // ✅ Sums all different amounts
//                 $entriesToUpdate[] = [
//                     'id' => $entry->id,
//                     'current_received' => $entry->total_received,
//                     'daily_amount' => $dailyIncome,
//                     'topup_amount' => $entry->amount // Track original amount
//                 ];
//             }
//         }

//         if ($totalDailyIncome <= 0) {
//             continue;
//         }

//         // Apply capping to total
//         if ($totalDailyIncome > $remainingCap) {
//             $totalDailyIncome = $remainingCap;
//         }

//         try {
//             // ✅ Insert SINGLE aggregated entry (sum of all topups)
//             DB::table('member_income_history')->insert([
//                 'member_id' => $memberId,
//                 'amount' => $totalDailyIncome, // Combined from ALL topups
//                 'date' => $todayTimestamp,
//                 'member_daily_income_id' => $memberEntries->first()->id,
//                 'created_at' => $currentTimestamp,
//                 'updated_at' => $currentTimestamp,
//             ]);

//             \App\Helpers\PaymentLogHelper::log(
//                 type: 'earning',
//                 member_id: $memberId,
//                 sub_type: 'daily_income',
//                 amount: $totalDailyIncome,
//                 direction: 'debit',
//                 source: 'system',
//                 description: "Daily ROI ₹{$totalDailyIncome} credited to {$memberId}",
//                 remarks: "From " . count($memberEntries) . " topups: " . 
//                          implode(', ', array_column($entriesToUpdate, 'topup_amount'))
//             );

//             // ✅ Update each topup proportionally based on its contribution
//             $originalTotal = array_sum(array_column($entriesToUpdate, 'daily_amount'));
//             $actualProportion = $totalDailyIncome / $originalTotal;

//             foreach ($entriesToUpdate as $updateInfo) {
//                 $actualDaily = $updateInfo['daily_amount'] * $actualProportion;
                
//                 DB::table('member_daily_income')
//                     ->where('id', $updateInfo['id'])
//                     ->update([
//                         'total_received' => $updateInfo['current_received'] + $actualDaily,
//                         'updated_at' => $currentTimestamp,
//                     ]);
//             }

//             $processedCount++;

//         } catch (\Exception $e) {
//             Log::error("Daily income processing failed for member {$memberId}: " . $e->getMessage());
//         }
//     }

//     echo "Daily income processing completed. Processed {$processedCount} members.";
// }





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

            // Per-link cap enforcement
            if ($totalReceived + $perDay > $cap) {
                $perDay = $cap - $totalReceived;
            }

            // Global cumulative cap enforcement
            $earnings = WalletHelper::getMemberEarnings($sponsorId);
            $totalEarned = $earnings['total_income'];
            $totalCap = (int) DB::table('member')
                ->where('show_mem_id', $sponsorId)
                ->value('tot_cpping_amt');
            $remainingCap = max($totalCap - $totalEarned, 0);

            // Split into credited and lapsed amounts
            $lapAmount = 0;
            $creditedAmount = $perDay;

            if ($creditedAmount > $remainingCap) {
                $lapAmount = $creditedAmount - $remainingCap;
                $creditedAmount = $remainingCap;
            }

            if ($creditedAmount <= 0) {
                // Fully lapsed — log it
                if ($perDay > 0) {
                    WalletHelper::storeLapsAmountLog($sponsorId, $perDay, 'Full lapse from sponsor daily income');
                }
                continue;
            }

            // ✅ ONLY CHANGE: Check duplicate using unique PIN ID + today's date
            $alreadyExists = DB::table('payment_logs') // or whatever your payment log table name is
                ->where('member_id', $sponsorId)
                ->where('sub_type', 'sponsor_daily_income')
                ->whereRaw("description LIKE '%Record #{$record->id}%'") // Check for this specific PIN ID
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
                description: "Sponsor ROI ₹{$creditedAmount} credited to member {$sponsorId} (from {$fromId}) - Record #{$record->id}",
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

        // Mark due salary installments as credited (time-based)
        DB::table('salary_income')
            ->where('credited', 0)
            ->where('to_date', '<=', now()->timestamp)
            ->update(['credited' => 1]);

        foreach ($members as $mem) {
            // Get matching payouts since activation
            $payouts = DB::table('binary_payouts')
                ->where('member_id', $mem->show_mem_id)
                ->where('status', 1)
                // ->where('created_at', '>=', $mem->activate_date)
                ->orderBy('created_at')
                ->get();

            // Lapse the member if already at cap (safety)
            WalletHelper::checkSingleMemberCapping($mem->show_mem_id);

            if ($payouts->isEmpty()) {
                continue;
            }

            // Existing slabs already scheduled (to avoid duplication)
            $existingSlabs = DB::table('salary_income')
                ->where('member_id', $mem->show_mem_id)
                ->pluck('matching_income')
                ->toArray();

            $cumulativeMatching = 0;
            $processedSlabs = [];

            foreach ($payouts as $payout) {
                $cumulativeMatching += (int)$payout->tot_matching;

                foreach ($this->salarySlabs as $slabMatch => $salaryAmount) {
                    // If member has reached the matching threshold and slab not yet processed/scheduled
                    if (
                        $cumulativeMatching >= $slabMatch &&
                        !in_array($slabMatch, $processedSlabs) &&
                        !in_array($slabMatch, $existingSlabs)
                    ) {
                        // Cumulative cap model: remainingCap = tot_cpping_amt - total_income
                        $totalEarned = WalletHelper::getMemberEarnings($mem->show_mem_id)['total_income'];
                        $totalCap = (int) DB::table('member')
                            ->where('show_mem_id', $mem->show_mem_id)
                            ->value('tot_cpping_amt');
                        $remainingCap = max($totalCap - $totalEarned, 0);

                        // Full 6-month total for this slab
                        $totalSalaryToPay = $salaryAmount * 6;
                        $lapAmount = 0;

                        // If there is ZERO capacity now, do NOT insert any rows.
                        // This keeps the slab eligible to be retried in a later month after re-topup,
                        // so Month 1 is missed and Month 2 (or later) will become the new Month 1 when capacity exists.
                        if ($remainingCap <= 0) {
                            // Optional: keep or remove this log; it's only for visibility, not a permanent lapse.
                            // WalletHelper::storeLapsAmountLog($mem->show_mem_id, $totalSalaryToPay, "Full lapse (no schedule created): Salary slab â‚¹{$slabMatch}");
                            continue;
                        }

                        // If partial capacity, trim total now
                        if ($totalSalaryToPay > $remainingCap) {
                            $lapAmount = $totalSalaryToPay - $remainingCap;
                            $totalSalaryToPay = $remainingCap;
                        }

                        // If still nothing payable after trim, skip and optionally log
                        if ($totalSalaryToPay <= 0) {
                            if ($lapAmount > 0) {
                                WalletHelper::storeLapsAmountLog($mem->show_mem_id, $lapAmount, "Lapsed due to cap for salary slab â‚¹{$slabMatch}");
                            }
                            continue;
                        }

                        // Create a 6-month schedule starting from NOW (current month).
                        // This ensures if Month 1 was missed due to cap, we do not back-credit it.
                        // The first payable month becomes "this" month (or the month when capacity exists).
                        $monthlySalary = (int) floor($totalSalaryToPay / 6);
                        $base = Carbon::now()->startOfDay();

                        for ($i = 0; $i < 6; $i++) {
                            $salaryDate = $base->copy()->addMonths($i);

                            $salaryIncomeId = DB::table('salary_income')->insertGetId([
                                'member_id'        => $mem->show_mem_id,
                                'amount'           => $monthlySalary,
                                'matching_income'  => $slabMatch,
                                'from_date'        => $base->timestamp,
                                'to_date'          => $salaryDate->timestamp,
                                'created_at'       => now()->timestamp,
                                'updated_at'       => now()->timestamp,
                            ]);

                            \App\Helpers\PaymentLogHelper::log(
                                type: 'income',
                                member_id: $mem->show_mem_id,
                                sub_type: 'salary_income',
                                amount: $monthlySalary,
                                direction: 'debit', // keep your existing convention
                                source: 'salary_bonus',
                                description: "Salary â‚¹{$monthlySalary} from slab â‚¹{$slabMatch} to member {$mem->show_mem_id}",
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

                        // Log any lapsed portion (visibility only; no back-credit)
                        if ($lapAmount > 0) {
                            WalletHelper::storeLapsAmountLog($mem->show_mem_id, $lapAmount, "Partial lapse from salary slab â‚¹{$slabMatch}");
                        }

                        // Mark this slab as processed to avoid duplicate schedules
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

                // Cumulative cap model
                $totalEarned = WalletHelper::getMemberEarnings($mem->show_mem_id)['total_income'];
                $totalCap = (int) DB::table('member')
                    ->where('show_mem_id', $mem->show_mem_id)
                    ->value('tot_cpping_amt');
                $remainingCap = max($totalCap - $totalEarned, 0);

                $finalReward = $data['amount'];
                $lapsAmount = 0;

                if ($remainingCap <= 0) {
                    WalletHelper::storeLapsAmountLog(
                        $mem->show_mem_id,
                        $finalReward,
                        "Reward lapsed (Rank: {$data['rank']}, Pairs: {$requiredPairs}) due to full cap",
                        $requiredPairs
                    );
                    continue;
                }

                if ($finalReward > $remainingCap) {
                    $lapsAmount = $finalReward - $remainingCap;
                    $finalReward = $remainingCap;
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

                // âœ… Insert reward
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
                    description: "Reward â‚¹{$finalReward} granted to member {$member->memid} â€” Rank: {$data['rank']}",
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

                echo "âœ… Reward inserted for {$member->memid} â€” Rank: {$data['rank']} â€” â‚¹{$finalReward} â€” Used {$requiredPairs} pairs\n";
            }
        }

        echo "Reward bonus processing completed.";
    }
}
