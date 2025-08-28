<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'category_tbl'; // Set the table name if different
    protected $primaryKey = 'category_id'; // Specify primary key if it's not 'id'
    public $timestamps = false; // Disable timestamps if your table does not have them
}
