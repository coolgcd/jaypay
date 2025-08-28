<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RepurchaseMember extends Model
{
    use HasFactory;

    protected $table = 'repurchase_member_list';

    protected $fillable = [
        'memid',
        'selfpurchase',
        'cto',
        // add other fields as needed
    ];
}
