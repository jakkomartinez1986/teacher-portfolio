<?php

namespace App\Models\Settings\Document;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DocumentCategory extends Model
{
    //
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 
        'description'];
    protected $casts = [
        
        'deleted_at' => 'datetime',
    ];
    public function types()
    {
        return $this->hasMany(DocumentType::class, 'category_id');
    }
    public static function search($query)
    {
        return empty($query) ? static::query()
            : static::where('name', 'ilike', '%'.strtoupper($query).'%')
                ->orWhere('description', 'ilike', '%'.strtoupper($query).'%');
    }
}
