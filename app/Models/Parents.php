<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Parents extends Model
{
    use HasFactory;

    protected $fillable = [
        'father_name',
        'father_phone_number',
        'mother_name',
        'mother_phone_number',
    ];

    public function students(): HasMany
    {
        return $this->hasMany(Student::class)->select('id', 'first_name', 'last_name');
    }
}
