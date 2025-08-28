<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BinaryPin extends Model
{
    protected $table = 'binary_pin';

    // Enable Laravel auto-timestamps
    public $timestamps = true;

    protected $fillable = [
        'member_id',
        'pincode',
        'pinamt',
        'total_pv',
        'used_by',
        'used_at',
        'transfer_to',
        'transfer_date',
        'status',
        'joinid',
        'pintype',
        'pintp',
    ];
    public function usedMember()
{
    return $this->hasOne(Member::class, 'show_mem_id', 'joinid');
}
public function joinedMember()
{
    return $this->belongsTo(Member::class, 'joinid', 'show_mem_id');
}

public function admin()
{
    return $this->belongsTo(Admin::class, 'member_id');
}   


public function usedBy()
{
    return $this->belongsTo(Member::class, 'used_by');

}

}
