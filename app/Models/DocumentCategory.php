<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_id',
        'category_id',
    ];

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
} 