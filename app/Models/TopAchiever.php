<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TopAchiever extends Model
{
    use HasFactory;

    protected $table = 'top_achivers'; // Specify the table name
    protected $fillable = [
        'tatitle',
        'add_date',
        'status',
    ];
}
