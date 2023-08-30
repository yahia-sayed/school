<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'grade_id',
        'name',
        'total_marks',
    ];
    protected $hidden = [
        'grade_id',
        'created_at',
        'updated_at'
    ];
    public function grade(): BelongsTo
    {
        return $this->belongsTo(Grade::class)->select('id', 'name');
    }
    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(Teacher::class, 'subject_teacher')
            ->withPivot('id', 'start_date', 'end_date')->as('subject_teacher')
            ->select('teachers.id as teacher_id', 'teachers.full_name as teacher_name');
    }
}
