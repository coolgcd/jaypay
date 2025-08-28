<?php

if (!function_exists('getMemName')) {
    function getMemName($memId) {
        $member = \App\Models\Member::find($memId);
        return $member ? $member->name : 'Unknown';
    }
}
