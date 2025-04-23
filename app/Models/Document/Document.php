<?php

namespace App\Models\Document;

use App\Models\User;

use App\Models\Settings\School\Year;
use App\Models\Settings\Area\Subject;
use App\Models\Settings\School\Grade;
use Illuminate\Database\Eloquent\Model;
use App\Models\Settings\Trimester\Trimester;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Settings\Document\DocumentType;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Document extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'type_id',
        'creator_id',
        'year_id',
        'trimester_id',
        'title',
        'description',
        'file_path',
        'status',
        'is_shared'
    ];

    protected $casts = [
        'is_shared' => 'boolean'
    ];

    public function type()
    {
        return $this->belongsTo(DocumentType::class, 'type_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function year()
    {
        return $this->belongsTo(Year::class);
    }

    public function trimester()
    {
        return $this->belongsTo(Trimester::class);
    }

    public function authors()
    {
        return $this->belongsToMany(User::class, 'document_authors')
                   ->withPivot('is_main_author');
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'document_subject_grade')
                   ->withPivot('grade_id');
    }

    public function grades()
    {
        return $this->belongsToMany(Grade::class, 'document_subject_grade')
                   ->withPivot('subject_id');
    }

    public function signatures()
    {
        return $this->hasMany(DocumentSignature::class);
    }
    public static function search($query)
    {
        return empty($query) ? static::query()
            : static::where('title', 'like', '%'.strtoupper($query).'%')
                ->orWhere('description', 'like', '%'.strtoupper($query).'%');
    }
}
