<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'product_tbl';

    protected $fillable = [
        'pro_title', 'pro_price', 'dpprice', 'bv_value', 'joining'
    ];
}
