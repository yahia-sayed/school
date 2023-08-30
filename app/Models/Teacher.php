<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use function PHPSTORM_META\map;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'full_name',
        'email',
        'DOB',
        'phone_number',
        'salary',
        'CV',
        'area_id',
        'address'
    ];
    protected $hidden = [
        'CV',
        'user_id',
        'area_id'
    ];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }
    public function classrooms(): BelongsToMany
    {
        return $this->belongsToMany(Classroom::class, 'classroom_teacher')
            ->withPivot('id', 'start_date', 'end_date')->as('classroom_teacher');
    }
    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'subject_teacher')
            ->withPivot('id', 'start_date', 'end_date')->as('subject_teacher');
    }
}
