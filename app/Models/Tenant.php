<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tenant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        // 'property_id',  
        'address',
        'city',
        'state',
        'postal_code',
        'country',
        'emergency_contact',
        'emergency_phone',
        'notes',
    ];

    protected $casts = [
        'documents' => 'array',
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function leases(): HasMany
    {
        return $this->hasMany(Lease::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function maintenanceRequests(): HasMany
    {
        return $this->hasMany(MaintenanceRequest::class);
    }

    public function getActiveLeaseAttribute()
    {
        return $this->leases()
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->first();
    }

    public function getBalanceAttribute()
    {
        $activeLease = $this->activeLease;
        if (!$activeLease) {
            return 0;
        }

        $totalRent = $activeLease->monthly_rent;
        $totalPaid = $this->payments()
            ->where('lease_id', $activeLease->id)
            ->sum('amount');

        return $totalRent - $totalPaid;
    }
} 