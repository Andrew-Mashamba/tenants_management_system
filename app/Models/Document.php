<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'file_path',
        'file_name',
        'file_type',
        'file_size',
        'expiry_date',
        'requires_signature',
        'is_signed',
        'signed_at',
        'uploaded_by',
        'documentable_type',
        'documentable_id',
    ];

    protected $casts = [
        'expiry_date' => 'date',
        'signed_at' => 'datetime',
        'is_signed' => 'boolean',
        'requires_signature' => 'boolean',
    ];

    public function documentable(): MorphTo
    {
        return $this->morphTo();
    }

    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function versions(): HasMany
    {
        return $this->hasMany(DocumentVersion::class);
    }

    public function categories(): HasMany
    {
        return $this->hasMany(DocumentCategory::class);
    }

    public function expiryAlerts(): HasMany
    {
        return $this->hasMany(DocumentExpiryAlert::class);
    }
} 