<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Grade extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'fee',
        'total_marks',
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function getFeeAttribute($value): string
    {
        return number_format($value,0, null,',');
    }
    public function subjects(): HasMany
    {
        return $this->hasMany(Subject::class);
    }

    public function classrooms(): HasMany
    {
        return $this->hasMany(Classroom::class)->without('grade');
    }
    public function students(): HasMany
    {
        return $this->hasMany(Student::class, 'current_grade_id', 'id')
            ->select('id', 'first_name', 'last_name');
    }
}
