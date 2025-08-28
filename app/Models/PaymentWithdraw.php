<?php

// App\Models\PaymentWithdraw.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentWithdraw extends Model
{
    protected $table = 'payment_withdraw';

    public $timestamps = false; // if you don't have created_at / updated_at

    protected $fillable = [
        'member_id', 'request_date', 'tot_s_income', 'tot_level_income',
        'tot_withdraw', 'tot_balance', 'cur_withdraw_amt', 'deduction',
        'final_amt', 'status', 'confirmt_date', 'transaction_id', 'returnurl',
        'client_id', 'mobileno', 'accountname', 'accountno', 'ifsccode', 'updstatus'
    ];
}
