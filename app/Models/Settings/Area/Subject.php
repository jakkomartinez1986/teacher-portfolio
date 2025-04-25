<?php

namespace App\Models\Settings\Area;

use App\Models\Document\Document;
use App\Models\Settings\Area\Area;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subject extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'area_id',
        'subject_name' 
    ];

    protected $dates = ['deleted_at'];

    // Relación con Area
    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public static function search($query)
    {
        return empty($query) ? static::query()
            : static::where('subject_name', 'ilike', '%'.strtoupper($query).'%');
    }
   
    public function documents()
    {
        return $this->belongsToMany(Document::class, 'document_subject_grade')
                   ->withPivot('grade_id');
    }
}
