<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Helpers\WalletHelper;

class ProcessRewardBonus extends Command
{
    protected $signature = 'income:reward-bonus';
    protected $description = 'Process reward bonus based on matching business checkpoints';

    protected $rewardSlabs = [
        50000     => ['amount' => 1000,    'rank' => 'Bronze'],
        100000    => ['amount' => 2000,    'rank' => 'Silver'],
        500000    => ['amount' => 5000,    'rank' => 'Gold'],
        1000000   => ['amount' => 10000,   'rank' => 'Platinum'],
        2500000   => ['amount' => 25000,   'rank' => 'Ruby'],
        5000000   => ['amount' => 50000,   'rank' => 'Diamond'],
        10000000  => ['amount' => 100000,  'rank' => 'Crown'],
    ];

    public function handle()
    {
        $members = DB::table('binary_payouts')->where('status', 1)->get();

        foreach ($members as $member) {
            foreach ($this->rewardSlabs as $pair => $data) {
                if ((int)$member->tot_matching >= $pair) {
                    $exists = DB::table('reward_income')
                        ->where('member_id', $member->member_id)
                        ->where('matching_pair', $pair)
                        ->exists();

                    if ($exists) {
                        continue;
                    }

                    // ⚠️ CAP LOGIC STARTS
                    $mem = DB::table('member')->where('show_mem_id', $member->member_id)->first();
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

                    if ($totalEarned >= $capAmount) {
                        continue; // cap hit, skip reward
                    }

                    $finalReward = $data['amount'];
                    if (($totalEarned + $finalReward) > $capAmount) {
                        $finalReward = $capAmount - $totalEarned;
                        if ($finalReward <= 0) {
                            continue;
                        }
                    }
                    // ✅ CAP LOGIC ENDS

                    DB::table('reward_income')->insert([
                        'member_id'        => $member->member_id,
                        'matching_pair'    => $pair,
                        'rank'             => $data['rank'],
                        'amount'           => $finalReward,
                        'reward_cash'      => $finalReward,
                        'withdrawn_amount' => 0,
                        'status'           => 0,
                        'created_at'       => now(),
                        'updated_at'       => now(),
                    ]);

                    $rewardIncomeId = DB::getPdo()->lastInsertId();

                    DB::table('reward_income_logs')->insert([
                        'reward_income_id' => $rewardIncomeId,
                        'member_id'        => $member->member_id,
                        'amount'           => $finalReward,
                        'type'             => 'auto',
                        'created_at'       => now(),
                        'updated_at'       => now(),
                    ]);

                    $this->info("✅ Reward inserted for {$member->member_id} — Rank: {$data['rank']} — ₹{$finalReward}");
                }
            }
        }

        $this->info("Reward bonus processing completed.");
    }
}
