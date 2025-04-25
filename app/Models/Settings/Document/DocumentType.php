<?php

namespace App\Models\Settings\Document;

use App\Models\Document\Document;
use App\Models\Document\ApprovalFlow;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Settings\Document\DocumentCategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DocumentType extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id',
        'name',
        'description',
        'frequency',
        'requires_director',
        'requires_vice_principal',
        'requires_principal',
        'requires_dece'
    ];

    public function category()
    {
        return $this->belongsTo(DocumentCategory::class, 'category_id');
    }

    public function documents()
    {
        return $this->hasMany(Document::class, 'type_id');
    }

    public function approvalFlows()
    {
        return $this->hasMany(ApprovalFlow::class, 'document_type_id');
    }
    public static function search($query)
    {
        return empty($query) ? static::query()
            : static::where('name', 'ilike', '%'.strtoupper($query).'%')
                ->orWhere('description', 'ilike', '%'.strtoupper($query).'%');
    }
}
