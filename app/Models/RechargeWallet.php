<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RechargeWallet extends Model
{
    use HasFactory;

    protected $table = 'recharge_wallet'; // Specify the table name if different from the model name
    protected $fillable = ['memid', 'creditamt', 'add_date', 'purpose'];
}
