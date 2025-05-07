<?php

namespace App\Models\System\Teacher;

use App\Models\User;
use App\Models\Settings\Area\Subject;
use App\Models\Settings\School\Grade;
use Illuminate\Database\Eloquent\Model;
use App\Models\Settings\Trimester\Trimester;

class GradeEvaluation extends Model
{
    protected $fillable = [
        'subject_id',
        'grade_id',
        'trimester_id',
        'student_id',
        'teacher_id',
        'type',
        'activity_id',
        'description',
        'value',
        'date',
        'comments'
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'date' => 'date'
    ];

    // Relaciones
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    public function trimester()
    {
        return $this->belongsTo(Trimester::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function activity()
    {
        return $this->belongsTo(FormativeActivity::class);
    }

    // Scopes
    public function scopeFormative($query)
    {
        return $query->where('type', 'formative');
    }

    public function scopeSummative($query)
    {
        return $query->where('type', 'like', 'summative_%');
    }

    public function scopeForStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    public function scopeForSubject($query, $subjectId)
    {
        return $query->where('subject_id', $subjectId);
    }

    public function scopeForTrimester($query, $trimesterId)
    {
        return $query->where('trimester_id', $trimesterId);
    }
}
