<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberMonthIncome extends Model
{
    protected $table = 'member_month_income_laps'; // Assuming table name is member_month_income_laps

    // Relationships
    public function member()
    {
        return $this->belongsTo(Member::class, 'memid', 'mem_id');
    }
}
