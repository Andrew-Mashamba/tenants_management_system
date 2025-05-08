<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SmartLockAccessLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'smart_lock_id',
        'user_id',
        'access_type',
        'access_time',
        'status',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'access_time' => 'datetime',
    ];

    public function smartLock(): BelongsTo
    {
        return $this->belongsTo(SmartLock::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
} 