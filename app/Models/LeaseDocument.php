<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeaseDocument extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'lease_id',
        'name',
        'type',
        'file_path',
        'mime_type',
        'size',
        'description',
    ];

    protected $casts = [
        'expiry_date' => 'date',
        'requires_renewal' => 'boolean',
        'metadata' => 'array',
    ];

    public function lease(): BelongsTo
    {
        return $this->belongsTo(Lease::class);
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

    public function scopeType($query, $type)
    {
        return $query->where('type', $type);
    }
} 