<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class BulkMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject',
        'content',
        'channel',
        'recipients',
        'status',
        'scheduled_at',
        'sent_at',
        'delivery_status',
        'created_by'
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
        'recipients' => 'array',
        'delivery_status' => 'array'
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeDraft(Builder $query): Builder
    {
        return $query->where('status', 'draft');
    }

    public function scopeScheduled(Builder $query): Builder
    {
        return $query->where('status', 'scheduled');
    }

    public function scopeSent(Builder $query): Builder
    {
        return $query->where('status', 'sent');
    }

    public function scopeFailed(Builder $query): Builder
    {
        return $query->where('status', 'failed');
    }

    public function scopeReadyToSend(Builder $query): Builder
    {
        return $query->where('status', 'scheduled')
            ->where('scheduled_at', '<=', now());
    }

    public function markAsSending(): void
    {
        $this->update(['status' => 'sending']);
    }

    public function markAsSent(): void
    {
        $this->update([
            'status' => 'sent',
            'sent_at' => now()
        ]);
    }

    public function markAsFailed(): void
    {
        $this->update(['status' => 'failed']);
    }

    public function updateDeliveryStatus(int $recipientId, string $status, ?string $error = null): void
    {
        $deliveryStatus = $this->delivery_status ?? [];
        $deliveryStatus[$recipientId] = [
            'status' => $status,
            'error' => $error,
            'updated_at' => now()->toIso8601String()
        ];
        $this->update(['delivery_status' => $deliveryStatus]);
    }

    public function getRecipientCount(): int
    {
        return count($this->recipients ?? []);
    }

    public function getSuccessfulDeliveryCount(): int
    {
        return collect($this->delivery_status ?? [])
            ->where('status', 'delivered')
            ->count();
    }

    public function getFailedDeliveryCount(): int
    {
        return collect($this->delivery_status ?? [])
            ->where('status', 'failed')
            ->count();
    }
} 