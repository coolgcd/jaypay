<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberBankDetail extends Model
{
    use HasFactory;
    
    protected $table = 'member_bank_details';
    protected $primaryKey = 'id'; // Assuming there's an auto-increment id field
    public $timestamps = false; // Enable timestamps
    
    protected $fillable = [
        'member_id',
        'accname',
        'acctype',
        'acc_number',
        'bank_name',
        'branch',
        'ifsc_code',
        'address',
        'micr',
        'pannumber',
        'aadhar_number',
        'googlepay',
        'phonepay',
    ];
    
    // Relationship back to member
    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id', 'show_mem_id');
    }
}