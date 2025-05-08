<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeaseWorkflow extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'lease_id',
        'type',
        'status',
        'effective_date',
        'new_expiry_date',
        'reason',
        'steps',
        'initiated_by',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'effective_date' => 'date',
        'new_expiry_date' => 'date',
        'steps' => 'array',
        'approved_at' => 'datetime',
    ];

    public function lease(): BelongsTo
    {
        return $this->belongsTo(Lease::class);
    }

    public function initiator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'initiated_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function isPending(): bool
    {
        return $this->status === 'pending_approval';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function approve(User $approver): bool
    {
        return $this->update([
            'status' => 'approved',
            'approved_by' => $approver->id,
            'approved_at' => now(),
        ]);
    }

    public function reject(): bool
    {
        return $this->update(['status' => 'rejected']);
    }

    public function complete(): bool
    {
        return $this->update(['status' => 'completed']);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeType($query, $type)
    {
        return $query->where('type', $type);
    }
} 