<?php

namespace App\Models\Document;

use App\Models\User;
use App\Models\Document\Document;
use App\Models\Security\Spatie\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DocumentSignature extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_id',
        'user_id',
        'role_id',
        'signed_at',
        'comments',
        'status'
    ];

    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
