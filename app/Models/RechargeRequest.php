<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RechargeRequest extends Model
{
    protected $table = 'recharge_requests';

    protected $fillable = [
        'ref_id',
        'number',
        'amount',
        'operator',
        'status',
        'txn_id',
        'opt_id',
        'balance',
        'message',
    ];

    public $timestamps = true;
}
