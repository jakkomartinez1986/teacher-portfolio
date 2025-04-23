<?php

namespace App\Models\Document;

use App\Models\Security\Spatie\Role;
use Illuminate\Database\Eloquent\Model;
use App\Models\Settings\Document\DocumentType;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ApprovalFlow extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_type_id',
        'role_id',
        'approval_order',
        'is_mandatory'
    ];

    public function documentType()
    {
        return $this->belongsTo(DocumentType::class, 'document_type_id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
