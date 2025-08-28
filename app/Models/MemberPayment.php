<?php



namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'package_amount',
        'quantity',
        'total_amount',
        'screenshot_path',
        'status',
        'admin_remarks',
    ];
}

// namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;

// class MemberPayment extends Model
// {
//     use HasFactory;

//     protected $fillable = [
//         'member_id',
//         'package_amount',
//         'quantity',
//         'total_amount',
//         'screenshot_path',
//         'status',
//         'admin_remarks',
//     ];

//     public function member()
//     {
//         return $this->belongsTo(Member::class, 'member_id', 'show_mem_id');
//     }
// }
