<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'password',
        'email',
        'gender',
        'role',
        'picture',
        'status'
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'email_verified_at',
        'picture'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected array $statuses = array(
        '1' => 'active',
        '0' => 'inactive'
    );

    public function getStatusAttribute($value)
    {
        return $this->statuses[$value];
    }

    public function student(): HasOne
    {
        return $this->hasOne(Student::class);
    }

    public function teacher(): HasOne
    {
        return $this->hasOne(Teacher::class);
    }
}
