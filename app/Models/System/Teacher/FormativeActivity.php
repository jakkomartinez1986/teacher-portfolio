<?php

namespace App\Models\System\Teacher;

use App\Models\Settings\Area\Subject;
use Illuminate\Database\Eloquent\Model;
use App\Models\Settings\Trimester\Trimester;

class FormativeActivity extends Model
{
    protected $fillable = [
        'subject_id',
        'trimester_id',
        'name',
        'description',
        'due_date'
    ];

    protected $casts = [
        'due_date' => 'date'
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function trimester()
    {
        return $this->belongsTo(Trimester::class);
    }

    public function evaluations()
    {
        return $this->hasMany(GradeEvaluation::class, 'activity_id');
    }
}
