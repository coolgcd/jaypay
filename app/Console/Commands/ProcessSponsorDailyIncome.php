<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Helpers\WalletHelper;

class ProcessSponsorDailyIncome extends Command
{
    protected $signature = 'income:process-sponsor';
    protected $description = 'Process daily income for sponsors based on direct referrals';

    public function handle()
    {
        $now = Carbon::now()->timestamp;

        $records = DB::table('direct_payment_tbl')
            ->where('start_date', '<=', $now)
            ->where('end_date', '>=', $now)
            ->get();

        foreach ($records as $record) {
            $sponsorId = $record->member_id;
            $fromId = $record->from_id;
            $totalReceived = $record->total_received;
            $cap = $record->amount;
            $perDay = $cap * 0.005;

            // Fetch sponsor member info
          $member = DB::table('member')->where('show_mem_id', $sponsorId)->first();
            if (!$member) {
                $this->warn("Member not found: {$sponsorId}");
                continue;
            }

            $packageAmount = $member->payment ?? 0;

            // Determine if sponsor is a working member
          $isWorking = DB::table('member')
    ->where('sponsorid', $member->show_mem_id)
    ->where('status', 1)
    ->exists();

            $capAmount = $isWorking ? ($packageAmount * 3) : ($packageAmount * 2);

            // Fetch total earned from all income sources
            $earnings = WalletHelper::getMemberEarnings($member->show_mem_id);
            $totalEarned = $earnings['total_income'];

            $remainingCap = $capAmount - $totalEarned;
            if ($remainingCap <= 0) {
                $this->warn("Capping reached for sponsor: {$sponsorId}");
                continue;
            }

            if ($totalReceived >= $cap) {
                continue;
            }

            if ($totalReceived + $perDay > $cap) {
                $perDay = $cap - $totalReceived;
            }

            // Apply global cap limitation
            if ($perDay > $remainingCap) {
                $perDay = $remainingCap;
            }

            if ($perDay > 0) {
                DB::table('sponsor_daily_income')->insert([
                    'member_id'  => $sponsorId,
                    'from_id'    => $fromId,
                    'amount'     => $perDay,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                DB::table('direct_payment_tbl')
                    ->where('id', $record->id)
                    ->update([
                        'total_received' => $totalReceived + $perDay
                    ]);
            }
        }

        $this->info('Sponsor daily income processed successfully.');
    }
}
