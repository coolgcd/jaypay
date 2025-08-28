<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Helpers\WalletHelper;

class CalculateDailyIncome extends Command
{
    protected $signature = 'income:calculate';
    protected $description = 'Calculate and distribute 0.5% daily income to members';

    public function handle()
    {
        $currentTimestamp = Carbon::now()->timestamp;

        $eligibleMembers = DB::table('member_daily_income')
            ->where('start_date', '<=', $currentTimestamp)
            ->where(function ($q) use ($currentTimestamp) {
                $q->where('end_date', '>=', $currentTimestamp)
                  ->orWhereNull('end_date'); // support no-end-date rows
            })
            ->whereColumn('total_received', '<', 'amount')
            ->get();

        $processedCount = 0;

        foreach ($eligibleMembers as $entry) {
            // Fetch member info
           $member = DB::table('member')->where('show_mem_id', $entry->member_id)->first();
            if (!$member) {
                $this->warn("Member not found: {$entry->member_id}");
                continue;
            }

            $packageAmount = $member->payment ?? 0;

            // Determine if member is working (has at least one direct active referral)
          $isWorking = DB::table('member')
    ->where('sponsorid', $member->show_mem_id)
    ->where('status', 1)
    ->exists();

            $capAmount = $isWorking ? ($packageAmount * 3) : ($packageAmount * 2);

            // Calculate current total income
          $earnings = WalletHelper::getMemberEarnings($member->show_mem_id);
            $totalEarned = $earnings['total_income'];

            $remainingCap = $capAmount - $totalEarned;
            if ($remainingCap <= 0) {
               $this->warn("Capping reached for member: {$member->show_mem_id}");
                continue;
            }

            $dailyIncome = round($entry->amount * 0.005, 2); // 0.5%
            $remainingDaily = $entry->amount - $entry->total_received;

            if ($dailyIncome > $remainingDaily) {
                $dailyIncome = $remainingDaily;
            }

            // Enforce cap limit
            if ($dailyIncome > $remainingCap) {
                $dailyIncome = $remainingCap;
            }

            // Skip if daily income is 0 or negative
            if ($dailyIncome <= 0) {
                continue;
            }

            try {
                // Check if already processed today
                $existingEntry = DB::table('member_income_history')
                    ->where('member_id', $entry->member_id)
                    ->where('date', $currentTimestamp)
                    ->first();

                if ($existingEntry) {
                    $this->warn("Income already processed today for member: {$entry->member_id}");
                    continue;
                }

                // Log into income history
                DB::table('member_income_history')->insert([
                    'member_id' => $entry->member_id,
                    'amount' => $dailyIncome,
                    'date' => $currentTimestamp,
                    'created_at' => $currentTimestamp,
                    'updated_at' => $currentTimestamp,
                ]);

                // Update total received
                DB::table('member_daily_income')
                    ->where('id', $entry->id)
                    ->update([
                        'total_received' => $entry->total_received + $dailyIncome,
                        'updated_at' => $currentTimestamp,
                    ]);

                $processedCount++;
                $this->info("Processed {$dailyIncome} for member: {$entry->member_id}");

            } catch (\Exception $e) {
                $this->error("Error processing member {$entry->member_id}: " . $e->getMessage());
            }
        }

        $this->info("Daily income calculated for {$processedCount} members out of {$eligibleMembers->count()} eligible members.");

        return 0;
    }
}
