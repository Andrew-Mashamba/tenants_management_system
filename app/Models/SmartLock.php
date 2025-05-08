<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SmartLock extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'unit_id',
        'name',
        'device_id',
        'manufacturer',
        'model',
        'status',
        'last_sync_at',
        'settings',
    ];

    protected $casts = [
        'last_sync_at' => 'datetime',
        'settings' => 'array',
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function authorizedUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'smart_lock_users')
            ->withPivot('access_type', 'expires_at')
            ->withTimestamps();
    }

    public function accessLogs(): HasMany
    {
        return $this->hasMany(SmartLockAccessLog::class);
    }

    public function isOnline(): bool
    {
        return $this->status === 'online';
    }

    public function hasAccess(User $user): bool
    {
        return $this->authorizedUsers()
            ->where('user_id', $user->id)
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->exists();
    }
} 