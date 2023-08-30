<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Classroom_teacher extends Pivot
{
    use HasFactory;

    protected $table = 'classroom_teacher';

    protected $fillable = [
        'subject_teacher_id',
        'classroom_id',
        'start_date',
        'end_date'
    ];

}
