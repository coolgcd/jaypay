<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ManagePV extends Model
{
    protected $table = 'manage_pv';

    protected $fillable = [
        'pv_amount', 'withgst', 'pv_value', 'comment', 'status', 'capping', 'pintype'
    ];
}
