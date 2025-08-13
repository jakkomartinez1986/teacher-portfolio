<?php

namespace App\Models\System\Teacher;

use App\Models\User;
use App\Models\Settings\School\Year;
use App\Models\Settings\Area\Subject;
use App\Models\Settings\School\Grade;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClassSchedule extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'year_id',
        'teacher_id',
        'subject_id',
        'grade_id',
        'trimester_id',
        'day',
        'start_time',
        'end_time',
        'classroom',
        'is_active',
        'notes'
    ];

    protected $casts = [
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'is_active' => 'boolean'
    ];
    
    // Relaciones
    // public function year()
    // {
    //     return $this->belongsTo(Year::class, 'year_id');
    // }
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    // public function trimester()
    // {
    //     return $this->belongsTo(Trimester::class);
    // }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeCurrentTrimester($query)
    {
        return $query->whereHas('trimester', function($q) {
            $q->where('status', 1);
        });
    }

    // Atributos calculados
    public function getDurationAttribute()
    {
        return $this->start_time->diff($this->end_time)->format('%H:%I');
    }

    public function getScheduleAttribute()
    {
        return $this->start_time->format('H:i') . ' - ' . $this->end_time->format('H:i');
    }
}
