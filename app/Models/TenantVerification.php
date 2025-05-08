<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TenantVerification extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'verification_service_id',
        'verification_type',
        'verification_data',
        'status',
        'verified_at',
    ];

    protected $casts = [
        'verification_data' => 'array',
        'verified_at' => 'datetime',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function verificationService(): BelongsTo
    {
        return $this->belongsTo(VerificationService::class);
    }
} 