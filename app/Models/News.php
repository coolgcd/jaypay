<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    protected $table = 'news_tbl'; // Specify the table name
    protected $fillable = [
        'news',
        // Add other relevant fields here
    ];
}
