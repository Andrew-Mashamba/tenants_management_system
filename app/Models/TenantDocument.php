<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class TenantDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'title',
        'document_type',
        'file_path',
        'file_name',
        'mime_type',
        'file_size',
        'expiry_date',
        'requires_renewal',
        'description',
        'metadata',
    ];

    protected $casts = [
        'expiry_date' => 'date',
        'requires_renewal' => 'boolean',
        'metadata' => 'array',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function isExpired(): bool
    {
        return $this->expiry_date && $this->expiry_date->isPast();
    }

    public function isExpiringSoon(int $days = 30): bool
    {
        if (!$this->expiry_date) {
            return false;
        }

        return $this->expiry_date->subDays($days)->isPast() && !$this->isExpired();
    }

    public function getDownloadUrl(): string
    {
        return Storage::url($this->file_path);
    }

    public function delete()
    {
        Storage::delete($this->file_path);
        return parent::delete();
    }
}
