<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Helpers\WalletHelper;

class ProcessBinaryMatching extends Command
{
    protected $signature = 'process:binary-matching';
    protected $description = 'Calculate and store daily binary matching income';

    public function handle()
    {
        $now = time(); // Unix timestamp
        $nowCarbon = Carbon::now();

        $roots = DB::table('member_binary')->where('status', 1)->get();
        $this->info("Found " . $roots->count() . " active members");

        foreach ($roots as $root) {
            $this->info("Processing member: {$root->memid} - Left: '{$root->left}' - Right: '{$root->right}'");

            if (empty($root->left) || empty($root->right)) {
                $this->info("❌ Skipping {$root->memid} - Missing left or right child");
                continue;
            }

            $totalLeft = $this->sumPayAmount($root->left);
            $totalRight = $this->sumPayAmount($root->right);

            $this->info("Total Left: {$totalLeft}, Total Right: {$totalRight}");

            $lastPayout = DB::table('binary_payouts')
                ->where('member_id', $root->memid)
                ->orderByDesc('confirm_date')
                ->first();

            $usedLeft = $lastPayout ? (float) $lastPayout->used_left : 0;
            $usedRight = $lastPayout ? (float) $lastPayout->used_right : 0;

            $availableLeft = $totalLeft - $usedLeft;
            $availableRight = $totalRight - $usedRight;
            $matchable = min($availableLeft, $availableRight);

            $this->info("Matchable amount: {$matchable}");

            if ($matchable <= 0) {
                $this->info("❌ Skipping {$root->memid} - No matchable amount");
                continue;
            }

            $payamt = round($matchable * 0.06, 2); // 6% commission

            // CAP LOGIC
            $member = DB::table('member')->where('show_mem_id', $root->memid)->first();
            if (!$member) {
                $this->warn("❌ Member not found for memid: {$root->memid}");
                continue;
            }

            $packageAmount = $root->payamount ?? 0;

            $isWorking = DB::table('member')
                ->where('sponsorid', $member->show_mem_id)
                ->where('status', 1)
                ->exists();

            $capAmount = $isWorking ? ($packageAmount * 3) : ($packageAmount * 2);
            $totalEarned = WalletHelper::getMemberEarnings($member->show_mem_id)['total_income'];
            $remainingCap = $capAmount - $totalEarned;

            if ($remainingCap <= 0) {
                $this->warn("❌ Capping reached for member: {$root->memid}");
                continue;
            }

            if ($payamt > $remainingCap) {
                $payamt = $remainingCap;
            }

            // Prepare final values
            $cumulativeUsedLeft = $usedLeft + $matchable;
            $cumulativeUsedRight = $usedRight + $matchable;

            try {
                DB::table('binary_payouts')->insert([
                    'member_id'       => $root->memid,
                    'totleft_amount'  => (int) $totalLeft,
                    'totright_amount' => (int) $totalRight,
                    'used_left'       => $cumulativeUsedLeft,
                    'used_right'      => $cumulativeUsedRight,
                    'leftcarry'       => (int) ($totalLeft - $cumulativeUsedLeft),
                    'rightcarry'      => (int) ($totalRight - $cumulativeUsedRight),
                    'tot_matching'    => (int) $matchable,
                    'payamt'          => (int) $payamt,
                    'status'          => 0,
                    'confirm_date'    => $now,
                    'created_at'      => $now,
                    'updated_at'      => $now,
                ]);

                $this->info("✅ Matching payout recorded for member: {$root->memid} — ₹{$payamt} at " . $nowCarbon->format('Y-m-d H:i:s'));
            } catch (\Exception $e) {
                $this->error("❌ Error processing member {$root->memid}: " . $e->getMessage());
            }
        }

        return Command::SUCCESS;
    }

    private function sumPayAmount($memid)
    {
        if (empty($memid)) {
            return 0;
        }

        $total = 0;
        $visited = [];
        $stack = [$memid];

        while (!empty($stack)) {
            $current = array_pop($stack);

            if (in_array($current, $visited)) {
                continue;
            }

            $visited[] = $current;

            $node = DB::table('member_binary')->where('memid', $current)->first();

            if (!$node) {
                continue;
            }

            if ($node->status == 1) {
                $total += (float)$node->payamount;
            }

            if (!empty($node->left)) {
                $stack[] = $node->left;
            }

            if (!empty($node->right)) {
                $stack[] = $node->right;
            }
        }

        return $total;
    }
}