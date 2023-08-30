<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    use HasFactory;

    protected $table = 'password_reset';
    protected $primaryKey = 'email';

    public $timestamps = false;

    protected $fillable = [
        'email',
        'OTP',
        'created_at'
    ];
}
