<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Repurchase extends Model
{
    use HasFactory;

    protected $table = 'repurchase_tbl';

    protected $fillable = [
        'closing_date',
        'global_unit_rate',
        // add other fields as needed
    ];
}
