<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Area extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function students(): HasMany
    {
        return $this->hasMany(Student::class)->select('id', 'first_name', 'last_name');
    }

    public function teachers(): HasMany
    {
        return $this->hasMany(Teacher::class)->select('id', 'full_name');
    }
}
