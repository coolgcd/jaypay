<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LevelIncome extends Model
{
    use HasFactory;

    protected $table = 'mem_level_income';

    protected $fillable = [
        'memid', 'fromid', 'levid', 'rec_date', 'calcdate', 'rec_amt', 'status',
    ];

    public $timestamps = false;
}
