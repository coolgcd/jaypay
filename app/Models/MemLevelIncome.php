<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemLevelIncome extends Model
{
    protected $table = 'mem_level_income';
    protected $fillable = ['memid', 'rec_date', 'fromid', 'rec_amt', 'status'];

    public function member()
    {
        return $this->belongsTo(Member::class, 'memid', 'mem_id');
    }

    public function fromMember()
    {
        return $this->belongsTo(Member::class, 'fromid', 'mem_id');
    }
}
