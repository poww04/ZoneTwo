<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class Registration extends Model
{
    use HasFactory;

    protected $table = 'users'; 
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
    ];

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }
}
