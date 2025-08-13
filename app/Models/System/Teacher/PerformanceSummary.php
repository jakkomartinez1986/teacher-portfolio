<?php

namespace App\Models\System\Teacher;

use App\Models\User;
use App\Models\Settings\Area\Subject;
use App\Models\Settings\School\Grade;
use Illuminate\Database\Eloquent\Model;
use App\Models\Settings\Trimester\Trimester;

class PerformanceSummary extends Model
{
    protected $fillable = [
        'subject_id',
        'grade_id',
        'trimester_id',
        'student_id',
        'formative_scores',
        'integral_project',
        'final_evaluation',
        'formative_average',
        'summative_average',
        'final_grade',
        'grade_scale',
        'comments',
    ];

    protected $casts = [
        'formative_scores' => 'array',
        'integral_project' => 'decimal:2',
        'final_evaluation' => 'decimal:2',
        'formative_average' => 'decimal:2',
        'summative_average' => 'decimal:2',
        'final_grade' => 'decimal:2',
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

    // Método auxiliar para calcular promedio formativo (si lo necesitas desde aquí)
    public function calculateFormativeAverage(): float
    {
        $scores = collect($this->formative_scores)->flatten()->filter(fn ($val) => is_numeric($val));
        return $scores->count() > 0 ? round($scores->avg(), 2) : 0;
    }
}
