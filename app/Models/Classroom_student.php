<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classroom_student extends Model
{
    use HasFactory;

    protected $table = 'classroom_student';

    protected $fillable = [
        'student_id',
        'classroom_id',
        'start_date',
        'end_date',
    ];

}
