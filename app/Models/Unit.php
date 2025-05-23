<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Unit extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'property_id',
        'unit_number',
        'unit_type',
        'name',
        'type',
        'unit_id',
        'status',
        // 'rent_amount',
        'unit_price',
        'security_deposit',
        'square_feet',
        'bedrooms',
        'bathrooms',
        'description',
        'features',
        'amenities',
        // 'floor',
        // 'size',
        // 'availability_status',
        // 'available_from',
        // 'availability_notes',
        'tenant_id',
        'images',
        // 'last_maintenance_date',
        // 'maintenance_notes'
    ];

    protected $casts = [
        'features' => 'array',
        'amenities' => 'array',
        'images' => 'array',
        // 'available_from' => 'datetime',
        // 'last_maintenance_date' => 'datetime'
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function tenants(): HasMany
    {
        return $this->hasMany(Tenant::class);
    }

    public function leases(): HasMany
    {
        return $this->hasMany(Lease::class);
    }

    public function maintenanceRequests(): HasMany
    {
        return $this->hasMany(MaintenanceRequest::class);
    }

    public function lease(): HasOne
    {
        return $this->hasOne(Lease::class);
    }

    public function activeLease(): HasOne
    {
        return $this->hasOne(Lease::class)
            ->where('status', 'active')
            ->latest();
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(PropertyDocument::class);
    }

    public function isAvailable(): bool
    {
        return $this->availability_status === 'available' &&
               (!$this->available_from || $this->available_from->isPast());
    }

    public function isOccupied(): bool
    {
        return $this->availability_status === 'occupied';
    }

    public function isUnderMaintenance(): bool
    {
        return $this->availability_status === 'maintenance';
    }

    public function isReserved(): bool
    {
        return $this->availability_status === 'reserved';
    }

    public function updateAvailability(string $status, ?string $availableFrom = null, ?string $notes = null): self
    {
        $this->availability_status = $status;
        $this->available_from = $availableFrom;
        $this->availability_notes = $notes;
        $this->save();
        return $this;
    }

    public function hasActiveLease(): bool
    {
        return $this->activeLease()->exists();
    }

    public function getCurrentTenant(): ?Tenant
    {
        return $this->activeLease?->tenant;
    }

    public function scopeAvailable($query)
    {
        return $query->where('availability_status', 'available')
            ->where(function ($query) {
                $query->whereNull('available_from')
                    ->orWhere('available_from', '<=', now());
            });
    }

    public function scopeOccupied($query)
    {
        return $query->where('availability_status', 'occupied');
    }

    public function scopeUnderMaintenance($query)
    {
        return $query->where('availability_status', 'maintenance');
    }

    public function scopeReserved($query)
    {
        return $query->where('availability_status', 'reserved');
    }
}
