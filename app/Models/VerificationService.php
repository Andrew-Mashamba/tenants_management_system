<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VerificationService extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'service_type',
        'provider',
        'credentials',
        'is_active',
    ];

    protected $casts = [
        'credentials' => 'array',
        'is_active' => 'boolean',
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function verifications(): HasMany
    {
        return $this->hasMany(TenantVerification::class);
    }

    public function verifyTenant(Tenant $tenant, string $verificationType): array
    {
        // Implementation will depend on the specific provider
        // This is a placeholder for the actual verification logic
        return [
            'status' => 'pending',
            'data' => [],
        ];
    }
} 