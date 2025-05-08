<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Auth;

class Lease extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'property_id',
        'unit_id',
        'template_id',
        'start_date',
        'end_date',
        'rent_amount',
        'security_deposit',
        'status',
        'terms',
        'notes',
        'payment_frequency',
        'late_fee',
        'grace_period',
        'created_by'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'rent_amount' => 'decimal:2',
        'security_deposit' => 'decimal:2',
        'late_fee' => 'decimal:2',
        'terms' => 'array'
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

    public function template(): BelongsTo
    {
        return $this->belongsTo(LeaseTemplate::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(LeaseDocument::class);
    }

    public function workflows(): HasMany
    {
        return $this->hasMany(LeaseWorkflow::class);
    }

    public function activeWorkflow(): HasOne
    {
        return $this->hasOne(LeaseWorkflow::class)
            ->whereIn('status', ['initiated', 'pending_approval']);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function coTenants(): HasMany
    {
        return $this->hasMany(CoTenant::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function reminders(): HasMany
    {
        return $this->hasMany(LeaseReminder::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isExpired(): bool
    {
        return $this->end_date->isPast();
    }

    public function isExpiringSoon(int $days = 30): bool
    {
        return $this->end_date->subDays($days)->isPast() && !$this->isExpired();
    }

    public function initiateRenewal(array $data = []): LeaseWorkflow
    {
        return $this->workflows()->create([
            'type' => 'renewal',
            'status' => 'initiated',
            'effective_date' => $data['effective_date'] ?? null,
            'expiry_date' => $data['expiry_date'] ?? null,
            'reason' => $data['reason'] ?? null,
            'workflow_data' => $data['workflow_data'] ?? null,
            'initiated_by' => Auth::id(),
        ]);
    }

    public function initiateTermination(array $data = []): LeaseWorkflow
    {
        return $this->workflows()->create([
            'type' => 'termination',
            'status' => 'initiated',
            'effective_date' => $data['effective_date'] ?? null,
            'reason' => $data['reason'] ?? null,
            'workflow_data' => $data['workflow_data'] ?? null,
            'initiated_by' => Auth::id(),
        ]);
    }

    public function getTotalPaid(): float
    {
        return $this->payments()->sum('amount');
    }

    public function getBalance(): float
    {
        return $this->rent_amount - $this->getTotalPaid();
    }

    public function getDaysRemaining(): int
    {
        return now()->diffInDays($this->end_date, false);
    }

    public function hasActiveWorkflow(): bool
    {
        return $this->activeWorkflow()->exists();
    }

    public function hasOverduePayments(): bool
    {
        return $this->invoices()
            ->where('status', 'unpaid')
            ->where('due_date', '<', now())
            ->exists();
    }

    public function getOverdueAmount(): float
    {
        return $this->invoices()
            ->where('status', 'unpaid')
            ->where('due_date', '<', now())
            ->sum('balance');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeExpired($query)
    {
        return $query->where('status', 'expired');
    }

    public function scopeExpiringSoon($query, $days = 30)
    {
        return $query->where('status', 'active')
            ->where('end_date', '<=', now()->addDays($days))
            ->where('end_date', '>', now());
    }
}
