<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccessLog extends Model
{
    protected $fillable = [
        'smart_lock_id',
        'user_id',
        'access_type',
        'status',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
        'created_at' => 'datetime',
    ];

    public function smartLock(): BelongsTo
    {
        return $this->belongsTo(SmartLock::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function wasSuccessful(): bool
    {
        return $this->status === 'success';
    }

    public function getAccessTypeLabel(): string
    {
        return match($this->access_type) {
            'unlock' => 'Unlock',
            'lock' => 'Lock',
            'check_status' => 'Status Check',
            default => ucfirst($this->access_type),
        };
    }
} 