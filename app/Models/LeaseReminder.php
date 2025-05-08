<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeaseReminder extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'lease_id',
        'type',
        'reminder_date',
        'is_sent',
        'sent_at',
        'message',
    ];

    protected $casts = [
        'reminder_date' => 'date',
        'sent_at' => 'datetime',
        'is_sent' => 'boolean',
    ];

    public function lease()
    {
        return $this->belongsTo(Lease::class);
    }

    public function scopePending($query)
    {
        return $query->where('is_sent', false);
    }

    public function scopeType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function markAsSent()
    {
        $this->update([
            'is_sent' => true,
            'sent_at' => now(),
        ]);
    }
} 