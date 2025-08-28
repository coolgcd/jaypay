<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Member extends Authenticatable
{
    use HasFactory;
    
    protected $table = 'member';
    protected $primaryKey = 'show_mem_id';
    public $incrementing = false; // Since show_mem_id might not be auto-incrementing
    protected $keyType = 'string';
    public $timestamps = false; // Enable timestamps
    
    // Add all the fields that can be mass assigned
    protected $fillable = [
        'show_mem_id',
        'name',
        'father_name',
        'gender',
        'emailid',
        'mobileno',
        'dob',
        'sponsorid',
        'joindate',
        'activate_date',
        'pannumber',
        'address',
        'city',
        'state',
        'pincode',
        'status',
        'parentid',
        'password',
        // Add any other fields that exist in your member table
    ];
    
    protected $hidden = ['password'];
    
    // Specify the timestamp column names if they're different from default
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    
    public function getAuthIdentifierName()
    {
        return 'show_mem_id';
    }
    
    // Relationship to binary tree
    public function memberBinary()
    {
        return $this->hasOne(MemberBinary::class, 'memid', 'show_mem_id');
    }
    
    // Get sponsor relationship
    public function sponsor()
    {
        return $this->belongsTo(Member::class, 'sponsorid', 'show_mem_id');
    }
    
    // Get parent relationship
    public function parent()
    {
        return $this->belongsTo(Member::class, 'parentid', 'show_mem_id');
    }
    
    // Bank details relationship
    public function member_bank_details()
    {
        return $this->hasOne(MemberBankDetail::class, 'member_id', 'show_mem_id');
    }
    
    // Helper method
    public static function getNameById($member_Id)
    {
        $member = self::where('show_mem_id', $member_Id)->first();
        return $member ? $member->name : 'Unknown';
    }

    public function withdraws()
{
    return $this->hasMany(PaymentWithdraw::class, 'member_id', 'show_mem_id');
}

public function bankDetail()
{
    return $this->hasOne(MemberBankDetail::class, 'member_id', 'show_mem_id');
}

}