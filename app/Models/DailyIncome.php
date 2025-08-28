<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyIncome extends Model
{
    protected $fillable = [
        'member_id', 'amount', 'start_date', 'end_date', 'total_received',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }
}
