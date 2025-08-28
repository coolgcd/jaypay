<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartDetail extends Model
{
    use HasFactory;

    protected $fillable = ['orderid', 'stokist_dist', 'memid', 'mem_name', 'mobile', 'tot_bv', 'tot_amt', 'shipping_chrg', 'order_date', 'status'];

    // Define the relationship to CartPayment
    public function cartPayment()
    {
        return $this->hasOne(CartPayment::class, 'orderid', 'orderid');
    }
}
