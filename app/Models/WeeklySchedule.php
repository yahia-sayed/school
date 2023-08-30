<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WeeklySchedule extends Model
{
    use HasFactory;

    protected $table = 'weekly_schedules';

    protected $fillable = [
        'classroom_teacher_id',
        'day',
        'time'
    ];

}
