<?php

namespace App\Models\System\Attendance;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use App\Models\System\Teacher\ClassSchedule;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClassObservation extends Model
{
    protected $fillable = [
        'class_schedule_id',
        'tutor_id',
        'teacher_id',
        'observation_date',
        'classtopic',
        'observation'
    ];

    protected $casts = [
        'observation_date' => 'date'
    ];

    public function classSchedule(): BelongsTo
    {
        return $this->belongsTo(ClassSchedule::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

     public function tutor()
    {
        return $this->belongsTo(User::class, 'tutor_id');
    }
    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'class_observation_id');
    }
}