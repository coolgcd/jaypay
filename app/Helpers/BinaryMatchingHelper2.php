<?php



namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Helpers\WalletHelper;

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

            $hasLeft = DB::table('member_binary')
                ->where('sponsor_id', $root->memid)
                ->where('position', 'left')
                ->where('status', 1)
                ->exists();

            $hasRight = DB::table('member_binary')
                ->where('sponsor_id', $root->memid)
                ->where('position', 'right')
                ->where('status', 1)
                ->exists();

            if (!($hasLeft && $hasRight)) {
                continue;
            }

            $totalLeft  = self::sumSponsorTreeBusiness($root->memid, 'left');
            $totalRight = self::sumSponsorTreeBusiness($root->memid, 'right');

            $matchable = min(
                $totalLeft - self::getUsed($root->memid, 'left'),
                $totalRight - self::getUsed($root->memid, 'right')
            );

            if ($matchable <= 0) {
                continue;
            }

            $payamt = round($matchable * 0.06, 2);

            $member = DB::table('member')->where('show_mem_id', $root->memid)->first();
            if (!$member) continue;

            $isWorking = DB::table('member')
                ->where('sponsorid', $member->show_mem_id)
                ->where('status', 1)
                ->exists();

            $cap = $isWorking ? ($root->payamount * 3) : ($root->payamount * 2);
            $totalEarned = WalletHelper::getMemberEarnings($member->show_mem_id)['total_income'];
            $remainingCap = $cap - $totalEarned;

            if ($remainingCap <= 0) continue;
            if ($payamt > $remainingCap) $payamt = $remainingCap;

            try {
                DB::table('binary_payouts')->insert([
                    'member_id'       => $root->memid,
                    'totleft_amount'  => (int) $totalLeft,
                    'totright_amount' => (int) $totalRight,
                    'used_left'       => (float) $totalLeft,
                    'used_right'      => (float) $totalRight,
                    'leftcarry'       => 0,
                    'rightcarry'      => 0,
                    'tot_matching'    => (float) $matchable,
                    'payamt'          => (float) $payamt,
                    'status'          => 1,
                    'confirm_date'    => $now,
                    'created_at'      => $now,
                    'updated_at'      => $now,
                ]);

                \App\Helpers\PaymentLogHelper::log(
                    type: 'income',
                    member_id: $root->memid,
                    sub_type: 'matching_income',
                    amount: $payamt,
                    direction: 'debit',
                    source: 'binary_matching',
                    description: "Matching Income ₹{$payamt} paid to member {$root->memid}",
                    remarks: "From Left ₹{$totalLeft} / Right ₹{$totalRight}"
                );
            } catch (\Exception $e) {
                // Optionally log error
            }
        }
    }

    private static function sumSponsorTreeBusiness($sponsorMemid, $leg)
    {
        $total = 0;
        $stack = DB::table('member_binary')
            ->where('sponsor_id', $sponsorMemid)
            ->where('position', $leg)
            ->where('status', 1)
            ->pluck('memid')
            ->toArray();

        $visited = [];

        while (!empty($stack)) {
            $current = array_pop($stack);
            if (in_array($current, $visited)) continue;
            $visited[] = $current;

            $node = DB::table('member_binary')->where('memid', $current)->first();
            if (!$node) continue;

            if ($node->status == 1) {
                $total += (float) $node->payamount;
            }

            $children = DB::table('member_binary')
                ->where('sponsor_id', $current)
                ->where('status', 1)
                ->pluck('memid')
                ->toArray();

            $stack = array_merge($stack, $children);
        }

        return $total;
    }

    private static function getUsed($memid, $side)
    {
        $lastPayout = DB::table('binary_payouts')
            ->where('member_id', $memid)
            ->orderByDesc('confirm_date')
            ->first();

        return $lastPayout ? (float) ($side === 'left' ? $lastPayout->used_left : $lastPayout->used_right) : 0;
    }
}



// namespace App\Helpers;

// use Illuminate\Support\Facades\DB;
// use Carbon\Carbon;
// use App\Helpers\WalletHelper;

// class BinaryMatchingHelper
// {
//     public static function process()
//     {
//         $now = time();
//         $nowCarbon = Carbon::now();

//         $roots = DB::table('member_binary')->where('status', 1)->get();

//         foreach ($roots as $root) {
//             if (empty($root->left) || empty($root->right)) {
//                 continue;
//             }
//             $hasLeft = DB::table('member_binary')
//                 ->where('sponsor_id', $root->memid)
//                 ->where('position', 'left')
//                 ->where('status', 1)
//                 ->exists();

//             $hasRight = DB::table('member_binary')
//                 ->where('sponsor_id', $root->memid)
//                 ->where('position', 'right')
//                 ->where('status', 1)
//                 ->exists();

//             if (!($hasLeft && $hasRight)) {
//                 // ❌ Skip this member, no active personal recruits on both sides
//                 continue;
//             }


//             $totalLeft = self::sumSponsorTreeBusiness($root->memid, 'left');
// $totalRight = self::sumSponsorTreeBusiness($root->memid, 'right');

//             $matchable = min($totalLeft - self::getUsed($root->memid, 'left'), $totalRight - self::getUsed($root->memid, 'right'));

//             if ($matchable <= 0) {
//                 continue;
//             }

//             $payamt = round($matchable * 0.06, 2);

//             $member = DB::table('member')->where('show_mem_id', $root->memid)->first();
//             if (!$member) continue;

//             $isWorking = DB::table('member')->where('sponsorid', $member->show_mem_id)->where('status', 1)->exists();
//             $cap = $isWorking ? ($root->payamount * 3) : ($root->payamount * 2);
//             $totalEarned = WalletHelper::getMemberEarnings($member->show_mem_id)['total_income'];
//             $remainingCap = $cap - $totalEarned;

//             if ($remainingCap <= 0) continue;
//             if ($payamt > $remainingCap) $payamt = $remainingCap;

//             try {
//                 DB::table('binary_payouts')->insert([
//                     'member_id'       => $root->memid,
//                     'totleft_amount'  => (int) $totalLeft,
//                     'totright_amount' => (int) $totalRight,
//                     'used_left'       => (float) $totalLeft,
//                     'used_right'      => (float) $totalRight,
//                     'leftcarry'       => 0,
//                     'rightcarry'      => 0,
//                     'tot_matching'    => (float) $matchable,
//                     'payamt'          => (float) $payamt,
//                     'status'          => 1,
//                     'confirm_date'    => $now,
//                     'created_at'      => $now,
//                     'updated_at'      => $now,
//                 ]);

//                 \App\Helpers\PaymentLogHelper::log(
//                     type: 'income',
//                     member_id: $root->memid, // still the member receiving income
//                     sub_type: 'matching_income',
//                     amount: $payamt,
//                     direction: 'debit', // ✅ admin pays → system loss → debit
//                     source: 'binary_matching',
//                     description: "Matching Income ₹{$payamt} paid to member {$root->memid}",
//                     remarks: "From Left ₹{$totalLeft} / Right ₹{$totalRight}"
//                 );
//             } catch (\Exception $e) {
//                 // You can optionally log this
//             }
//         }
//     }

//     private static function sumPayAmount($memid)
//     {
//         if (empty($memid)) return 0;

//         $total = 0;
//         $visited = [];
//         $stack = [$memid];

//         while (!empty($stack)) {
//             $current = array_pop($stack);
//             if (in_array($current, $visited)) continue;

//             $visited[] = $current;
//             $node = DB::table('member_binary')->where('memid', $current)->first();
//             if (!$node) continue;

//             if ($node->status == 1) {
//                 $total += (float) $node->payamount;
//             }

//             if (!empty($node->left)) $stack[] = $node->left;
//             if (!empty($node->right)) $stack[] = $node->right;
//         }

//         return $total;
//     }

//     private static function sumSponsorTreeBusiness($sponsorMemid, $leg)
// {
//     // Get all members in sponsor chain on this leg
//     $members = DB::table('member_binary')
//         ->where('sponsor_id', $sponsorMemid)
//         ->where('position', $leg)
//         ->where('status', 1)
//         ->pluck('memid');

//     $total = 0;

//     foreach ($members as $memid) {
//         // Get this member's binary business under their placement tree
//         $total += self::sumPayAmount($memid); // already traverses .left/.right tree
//     }

//     return $total;
// }


//     private static function getUsed($memid, $side)
//     {
//         $lastPayout = DB::table('binary_payouts')
//             ->where('member_id', $memid)
//             ->orderByDesc('confirm_date')
//             ->first();

//         return $lastPayout ? (float) ($side === 'left' ? $lastPayout->used_left : $lastPayout->used_right) : 0;
//     }
// }
