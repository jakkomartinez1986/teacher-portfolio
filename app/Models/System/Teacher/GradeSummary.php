<?php

namespace App\Models\System\Teacher;

use App\Models\User;
use App\Models\Settings\Area\Subject;
use App\Models\Settings\School\Grade;
use Illuminate\Database\Eloquent\Model;
use App\Models\Settings\Trimester\Trimester;
use App\Models\Settings\School\GradingSetting;

class GradeSummary extends Model
{
    protected $fillable = [
        'subject_id',
        'grade_id',
        'trimester_id',
        'student_id',
        'formative_avg',
        'summative_exam',
        'summative_project',
        'final_grade',
        'comments',
        'approved'
    ];

    protected $casts = [
        'formative_avg' => 'decimal:2',
        'summative_exam' => 'decimal:2',
        'summative_project' => 'decimal:2',
        'final_grade' => 'decimal:2',
        'approved' => 'boolean'
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

    // Métodos
    public function recalculateFinalGrade()
    {
        $config = GradingSetting::where('year_id', $this->trimester->year_id)->first();
        
        $summativeTotal = ($this->summative_exam + $this->summative_project) / 2;
        
        $this->final_grade = 
            ($this->formative_avg * $config->formative_percentage / 100) + 
            ($summativeTotal * $config->summative_percentage / 100);
            
        $this->approved = $this->final_grade >= 7;
        $this->save();
    }
}
