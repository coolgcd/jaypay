<?php
// app/Helpers/PaymentLogHelper.php

namespace App\Helpers;

use App\Models\PaymentLog;

class PaymentLogHelper
{
    // Main logging method
    public static function log($type, $member_id, $sub_type, $amount, $direction = 'debit', $source = null, $description = null, $remarks = null)
    {
        return PaymentLog::create([
            'member_id' => $member_id,
            'type' => $type,
            'sub_type' => $sub_type,
            'amount' => $amount,
            'direction' => $direction,
            'source' => $source,
            'description' => $description,
            'remarks' => $remarks,
        ]);
    }

    // MEMBER PAYMENT METHODS
    public static function logMemberPayment($member_id, $amount, $direction, $source, $remarks = null)
    {
        $sub_type = $direction === 'credit' ? 'admin_credit' : 'member_debit';
        $description = $direction === 'credit' 
            ? "Admin credited ₹{$amount} to member {$member_id}"
            : "Member {$member_id} debited ₹{$amount}";

        return self::log('member_payment', $member_id, $sub_type, $amount, $direction, $source, $description, $remarks);
    }

    // TPIN GENERATION
    public static function logTpinGeneration($member_id, $tpin_fee = 0, $remarks = null)
    {
        $description = "TPIN generated for member {$member_id}";
        if ($tpin_fee > 0) {
            $description .= " (Fee: ₹{$tpin_fee})";
        }

        return self::log('tpin_issued', $member_id, 'tpin_generation', $tpin_fee, 'debit', 'system', $description, $remarks);
    }

    // MEMBER INCOME METHODS
    public static function logDirectIncome($member_id, $amount, $from_member_id = null, $remarks = null)
    {
        $description = "Direct income ₹{$amount} credited to member {$member_id}";
        if ($from_member_id) {
            $description .= " from member {$from_member_id}";
        }

        return self::log('income', $member_id, 'direct_income', $amount, 'credit', 'system', $description, $remarks);
    }

    public static function logLevelIncome($member_id, $amount, $level, $from_member_id = null, $remarks = null)
    {
        $description = "Level {$level} income ₹{$amount} credited to member {$member_id}";
        if ($from_member_id) {
            $description .= " from member {$from_member_id}";
        }

        return self::log('income', $member_id, 'level_income', $amount, 'credit', 'system', $description, $remarks);
    }

    public static function logMatchingIncome($member_id, $amount, $remarks = null)
    {
        $description = "Matching income ₹{$amount} credited to member {$member_id}";

        return self::log('income', $member_id, 'matching_income', $amount, 'credit', 'system', $description, $remarks);
    }

    public static function logLeadershipIncome($member_id, $amount, $remarks = null)
    {
        $description = "Leadership income ₹{$amount} credited to member {$member_id}";

        return self::log('income', $member_id, 'leadership_income', $amount, 'credit', 'system', $description, $remarks);
    }

    public static function logRoyaltyIncome($member_id, $amount, $remarks = null)
    {
        $description = "Royalty income ₹{$amount} credited to member {$member_id}";

        return self::log('income', $member_id, 'royalty_income', $amount, 'credit', 'system', $description, $remarks);
    }

    // WITHDRAWAL
    public static function logWithdrawal($member_id, $amount, $status = 'pending', $remarks = null)
    {
        $description = "Withdrawal request ₹{$amount} by member {$member_id} - Status: {$status}";

        return self::log('withdrawal', $member_id, 'withdrawal_request', $amount, 'debit', 'member', $description, $remarks);
    }
}