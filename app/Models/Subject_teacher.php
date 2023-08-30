<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Subject_teacher extends Pivot
{
    use HasFactory;

    protected $table = 'subject_teacher';

    protected $fillable = [
        'subject_id',
        'teacher_id',
        'start_date',
        'end_date',
    ];
}
