<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DirectPayment extends Model
{
    protected $table = 'direct_payment_tbl';

    protected $fillable = [
        'member_id',      // sponsor
        'from_id',        // new member
        'amount',
        'start_date',
        'end_date',
        'total_received',
    ];

    public $timestamps = true; // optional: if you're not using created_at/updated_at
}
