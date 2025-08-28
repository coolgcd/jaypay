<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlyClosing extends Model
{
    use HasFactory;
    protected $table = 'monthly_closing_tbl';

    protected $fillable = [
        'memid', 'level_income', 'silver_club_income', // Add all necessary fields
    ];
}
