<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Closing extends Model
{
    protected $table = 'monthly_closing_tbl';

    // Only the closing_date is used
    protected $fillable = ['closing_date'];

    public $timestamps = false; // Assuming no timestamps for this table
}
