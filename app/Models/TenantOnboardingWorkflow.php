<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TenantOnboardingWorkflow extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'property_id',
        'unit_id',
        'status',
        'completed_steps',
        'pending_steps',
        'workflow_data',
        'completed_at',
        'rejection_reason',
    ];

    protected $casts = [
        'completed_steps' => 'array',
        'pending_steps' => 'array',
        'workflow_data' => 'array',
        'completed_at' => 'datetime',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function completeStep(string $step): void
    {
        $completedSteps = $this->completed_steps ?? [];
        $pendingSteps = $this->pending_steps ?? [];

        // Add to completed steps if not already completed
        if (!in_array($step, $completedSteps)) {
            $completedSteps[] = $step;
        }

        // Remove from pending steps
        $pendingSteps = array_diff($pendingSteps, [$step]);

        $this->update([
            'completed_steps' => $completedSteps,
            'pending_steps' => $pendingSteps,
        ]);
    }

    public function addPendingStep(string $step): void
    {
        $pendingSteps = $this->pending_steps ?? [];

        if (!in_array($step, $pendingSteps)) {
            $pendingSteps[] = $step;
            $this->update(['pending_steps' => $pendingSteps]);
        }
    }

    public function isStepCompleted(string $step): bool
    {
        return in_array($step, $this->completed_steps ?? []);
    }

    public function isStepPending(string $step): bool
    {
        return in_array($step, $this->pending_steps ?? []);
    }

    public function markAsCompleted(): void
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);
    }

    public function markAsRejected(string $reason): void
    {
        $this->update([
            'status' => 'rejected',
            'rejection_reason' => $reason,
        ]);
    }

    public function getProgress(): float
    {
        $totalSteps = count($this->completed_steps ?? []) + count($this->pending_steps ?? []);
        if ($totalSteps === 0) {
            return 0;
        }

        return (count($this->completed_steps ?? []) / $totalSteps) * 100;
    }
}
