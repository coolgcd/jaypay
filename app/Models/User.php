<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, HasApiTokens;

    // Allow mass assignment for these fields
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    // Your existing model code...
}
