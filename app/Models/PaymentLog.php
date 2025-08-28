<?php
// app/Models/PaymentLog.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentLog extends Model
{
    use HasFactory;
    
    protected $table = 'payment_logs';
    
    protected $fillable = [
        'member_id',
        'type',
        'sub_type',
        'amount',
        'direction',
        'source',
        'description',
        'remarks',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationship with Member
    public function member()
    {
        return $this->belongsTo(\App\Models\Member::class, 'member_id', 'show_mem_id');
    }

    // Accessor for Credit Amount (for view)
    public function getCreditAttribute()
    {
        return $this->direction === 'credit' ? $this->amount : null;
    }

    // Accessor for Debit Amount (for view)
    public function getDebitAttribute()
    {
        return $this->direction === 'debit' ? $this->amount : null;
    }

    // Scope for filtering by date range
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }
}