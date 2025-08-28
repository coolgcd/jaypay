<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UplineIncome extends Model
{
    use HasFactory;

    protected $table = 'upline_income'; // If the table name is different from the model name

    // Define the fillable fields if necessary
    protected $fillable = ['memid', 'rec_date', 'totamt', 'rec_amt', 'confirm_date'];
}
