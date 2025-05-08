<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vendor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'contact_person',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'postal_code',
        'country',
        'tax_id',
        'payment_terms',
        'notes',
        'status',
    ];

    protected $casts = [
        'service_areas' => 'array',
        'certifications' => 'array',
        'hourly_rate' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    public function services()
    {
        return $this->hasMany(VendorService::class);
    }

    public function maintenanceRequests(): HasMany
    {
        return $this->hasMany(MaintenanceRequest::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function activeServices()
    {
        return $this->services()->where('is_active', true);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeBySpecialization($query, $specialization)
    {
        return $query->where('specialization', $specialization);
    }

    public function scopeInServiceArea($query, $area)
    {
        return $query->whereJsonContains('service_areas', $area);
    }
} 