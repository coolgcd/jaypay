<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Helpers\WalletHelper;
use App\Helpers\PaymentLogHelper; // Make sure this helper exists and is correctly namespaced

class BinaryMatchingHelper
{
  

public static function process()
{
    $now = time();
    $roots = DB::table('member_binary')->where('status', 1)->get();

    foreach ($roots as $root) {
        if (empty($root->left) || empty($root->right)) {
            continue;
        }
         WalletHelper::checkSingleMemberCapping($root->memid);

        $hasLeftSponsor = DB::table('member_binary')
            ->where('sponsor_id', $root->memid)
            ->where('position', 'left')
            ->where('status', 1)
            ->exists();

        $hasRightSponsor = DB::table('member_binary')
            ->where('sponsor_id', $root->memid)
            ->where('position', 'right')
            ->where('status', 1)
            ->exists();

        if (!($hasLeftSponsor && $hasRightSponsor)) {
            continue;
        }

        $totalLeft = self::sumPlacementTreeBusiness($root->left);
        $totalRight = self::sumPlacementTreeBusiness($root->right);
        $lastPayout = self::getLastPayout($root->memid);
        $usedLeft = $lastPayout->used_left ?? 0;
        $usedRight = $lastPayout->used_right ?? 0;

        $matchable = min($totalLeft - $usedLeft, $totalRight - $usedRight);

        if ($matchable <= 0) {
            continue;
        }

        $payamt = round($matchable * 0.06, 2);
        if ($payamt <= 0) continue;

        $member = DB::table('member')->where('show_mem_id', $root->memid)->first();
        if (!$member) continue;

        $isWorking = DB::table('member')
            ->where('sponsorid', $member->show_mem_id)
            ->where('status', 1)
            ->exists();

        $cap = $isWorking ? ($root->payamount * 3) : ($root->payamount * 2);
        $totalEarned = WalletHelper::getMemberEarnings($member->show_mem_id)['total_income'];
        $remainingCap = $cap - $totalEarned;

        $lapsAmount = 0;

        if ($remainingCap <= 0) {
            WalletHelper::storeLapsAmountLog(
                $root->memid,
                $payamt,
                "Matching Income lapsed due to full cap"
            );
            continue;
        }

        if ($payamt > $remainingCap) {
            $lapsAmount = $payamt - $remainingCap;
            $payamt = $remainingCap;
        }

        if ($payamt <= 0) {
            if ($lapsAmount > 0) {
                WalletHelper::storeLapsAmountLog(
                    $root->memid,
                    $lapsAmount,
                    "Matching Income partially lapsed due to cap"
                );
            }
            continue;
        }

        try {
            DB::table('binary_payouts')->insert([
                'member_id'       => $root->memid,
                'totleft_amount'  => (int) $totalLeft,
                'totright_amount' => (int) $totalRight,
                'used_left'       => $usedLeft + $matchable,
                'used_right'      => $usedRight + $matchable,
                'leftcarry'       => ($totalLeft - $usedLeft) - $matchable,
                'rightcarry'      => ($totalRight - $usedRight) - $matchable,
                'tot_matching'    => (float) $matchable,
                'payamt'          => (float) $payamt,
                'status'          => 1,
                'confirm_date'    => $now,
                'created_at'      => $now,
                'updated_at'      => $now,
            ]);

            PaymentLogHelper::log(
                type: 'income',
                member_id: $root->memid,
                sub_type: 'matching_income',
                amount: $payamt,
                direction: 'debit',
                source: 'binary_matching',
                description: "Matching Income ₹{$payamt} paid to member {$root->memid}",
                remarks: "Matched business: ₹{$matchable}"
            );

            if ($lapsAmount > 0) {
                WalletHelper::storeLapsAmountLog(
                    $root->memid,
                    $lapsAmount,
                    "Matching Income partially lapsed due to cap"
                );
            }

        } catch (\Exception $e) {
            \Log::error("Binary matching failed for member {$root->memid}: " . $e->getMessage());
        }
    }
}

    /**
     * === NEW FUNCTION ===
     * Calculates total business volume for a downline leg by traversing the PLACEMENT tree.
     * This method correctly includes spillover business.
     *
     * @param string|null $startNodeMemid The member ID of the direct child at the top of the leg.
     * @return float The total business volume.
     */
    private static function sumPlacementTreeBusiness($startNodeMemid)
    {
        if (empty($startNodeMemid) || $startNodeMemid == '0') {
            return 0;
        }

        $total = 0;
        $stack = [$startNodeMemid]; // The stack starts with the direct child.
        $visited = []; // To prevent infinite loops in case of data corruption

        while (!empty($stack)) {
            $currentMemid = array_pop($stack);

            if (in_array($currentMemid, $visited)) continue;
            $visited[] = $currentMemid;

            $node = DB::table('member_binary')->where('memid', $currentMemid)->first();
            if (!$node) continue;

            // Sum the business amount if the node is active.
            if ($node->status == 1) {
                $total += (float) $node->payamount;
            }

            // Add the node's direct children to the stack to continue traversal.
            if (!empty($node->left) && $node->left != '0') {
                $stack[] = $node->left;
            }
            if (!empty($node->right) && $node->right != '0') {
                $stack[] = $node->right;
            }
        }

        return $total;
    }

    /**
     * === REVISED FUNCTION ===
     * Retrieves the last payout record to get the most recent used/carry amounts.
     *
     * @param string $memid The member's ID.
     * @return object|null The last payout record.
     */
    private static function getLastPayout($memid)
    {
        return DB::table('binary_payouts')
            ->where('member_id', $memid)
            ->orderByDesc('id') // Use the auto-incrementing primary key for the true latest record
            ->first();
    }
}
