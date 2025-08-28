<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberRechargeList extends Model
{
    use HasFactory;

    protected $table = 'member_recharge_list';

    protected $fillable = [
        'memid',
        'rech_type',
        'mobileno',
        'amount',
        'self_comm',
        'add_date',
        'rec_status',
        'opetator',
        'status'
    ];
}
