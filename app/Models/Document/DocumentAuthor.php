<?php

namespace App\Models\Document;

use App\Models\User;
use App\Models\Document\Document;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DocumentAuthor extends Model
{
    use HasFactory;

    protected $table = 'document_authors';

    protected $fillable = ['document_id', 'user_id', 'is_main_author'];

    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
