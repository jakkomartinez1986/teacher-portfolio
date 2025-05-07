<?php

namespace App\Models\Settings\School;

use Illuminate\Database\Eloquent\Model;

class GradingSetting extends Model
{
    protected $fillable = [
        'year_id',
        'formative_percentage',
        'summative_percentage',
        'exam_percentage',
        'project_percentage',
        'status'
    ]; 

    protected $casts = [
        'formative_percentage' => 'decimal:2',
        'summative_percentage' => 'decimal:2',
        'exam_percentage' => 'decimal:2',
        'project_percentage' => 'decimal:2',
        'status' => 'boolean'
    ];

    public function year()
    {
        return $this->belongsTo(Year::class);
    }
   
    public function validatePercentages()
    {
        $total = $this->formative_percentage + $this->summative_percentage;
        $summativeTotal = $this->exam_percentage + $this->project_percentage;

        return abs($total - 100) < 0.01 && abs($summativeTotal - $this->summative_percentage) < 0.01;
    }
    public static function search($query)
    {
        return empty($query) ? static::query()
            : static::where('formative_percentage', 'ilike', '%'.strtoupper($query).'%')
            ->orWhere('summative_percentage', 'ilike', '%'.strtoupper($query).'%');
    }
}
