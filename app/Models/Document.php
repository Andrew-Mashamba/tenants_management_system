<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'title',
        'name',
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
        'uploaded_by',
        'category',
        'status',
    ];

    protected $casts = [
        'expiry_date' => 'date',
        'signed_at' => 'datetime',
        'is_signed' => 'boolean',
        'requires_signature' => 'boolean',
        'file_size' => 'integer',
    ];

    public function documentable(): MorphTo
    {
        return $this->morphTo();
    }

    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function user(): BelongsTo
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

    public function getFormattedFileSizeAttribute()
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $index = 0;

        while ($bytes >= 1024 && $index < count($units) - 1) {
            $bytes /= 1024;
            $index++;
        }

        return round($bytes, 2) . ' ' . $units[$index];
    }

    public function getFileIconAttribute()
    {
        return match($this->file_type) {
            'application/pdf' => 'pdf',
            'image/jpeg', 'image/png', 'image/gif' => 'image',
            'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'word',
            'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'excel',
            default => 'file',
        };
    }
} 