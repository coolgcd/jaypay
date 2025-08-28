<?php

// File: app/Models/MemberDailyIncome.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberDailyIncome extends Model
{
    protected $table = 'member_daily_income';
    
    protected $fillable = [
        'member_id',
        'amount',
        'start_date',
        'end_date',
        'total_received',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'amount' => 'integer',
        'start_date' => 'integer',
        'end_date' => 'integer',
        'total_received' => 'integer',
        'created_at' => 'integer',
        'updated_at' => 'integer'
    ];

    // Relationship to Member
    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id', 'show_mem_id');
    }

    // Check if income period is active
    public function isActive()
    {
        $today = now()->timestamp;
        return $this->start_date <= $today && 
               $this->end_date >= $today && 
               $this->total_received < $this->amount;
    }

    // Get daily income amount (0.5%)
    public function getDailyIncomeAmount()
    {
        return round($this->amount * 0.005, 2);
    }

    // Get remaining amount to be distributed
    public function getRemainingAmount()
    {
        return $this->amount - $this->total_received;
    }
}