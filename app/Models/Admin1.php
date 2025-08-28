<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable;

class Admin extends Model implements AuthenticatableContract
{
    use HasFactory, Authenticatable;

    protected $fillable = [
        'name', 'email', 'password',
    ];

    // If you want to use timestamps, ensure this is set to true
    public $timestamps = true;

    // If you want to hash the password before saving it, you can override the saving event
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($admin) {
            $admin->password = bcrypt($admin->password);
        });
    }
}
