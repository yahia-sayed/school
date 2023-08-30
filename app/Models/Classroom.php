<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Classroom extends Model
{
    use HasFactory;

    protected $fillable = [
        'grade_id',
        'name',
    ];

    protected $hidden = [
        'grade_id',
        'created_at',
        'updated_at'
    ];
    protected $with = ['grade'];

    public function grade(): BelongsTo
    {
        return $this->belongsTo(Grade::class)->select('id', 'name');
    }
    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(Teacher::class, 'classroom_teacher')
            ->withPivot('id', 'start_date', 'end_date')->as('classroom_teacher')
            ->select('teachers.id', 'teachers.full_name');
    }
}
