<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberBinary extends Model
{
    use HasFactory;

    protected $table = 'member_binary';
    protected $primaryKey = 'memid';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'memid', 'position', 'tot_left', 'tot_right', 'parent', 
        'uplineid', 'sponsor_id', 'left', 'right', 'activ', 'activatedate'
    ];

    // Relationship to member
    public function member()
    {
        return $this->belongsTo(Member::class, 'memid', 'show_mem_id');
    }

    // Relationship to sponsor
    public function sponsor()
    {
        return $this->belongsTo(Member::class, 'sponsor_id', 'show_mem_id');
    }

    // Relationship to upline
    public function upline()
    {
        return $this->belongsTo(Member::class, 'uplineid', 'show_mem_id');
    }

    // Left child relationship
    public function leftChild()
    {
        return $this->belongsTo(MemberBinary::class, 'left', 'memid');
    }

    // Right child relationship
    public function rightChild()
    {
        return $this->belongsTo(MemberBinary::class, 'right', 'memid');
    }

    // Parent relationship
    public function parentMember()
    {
        return $this->belongsTo(MemberBinary::class, 'parent', 'memid');
    }
}