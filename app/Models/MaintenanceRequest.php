<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Auth;
use App\Notifications\MaintenanceStatusUpdateNotification;

class MaintenanceRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'unit_id',
        'tenant_id',
        'title',
        'description',
        'priority',
        'status',
        'assigned_to',
        'completed_date',
        'requested_date',
        'cost',
        'notes',
    ];

    protected $casts = [
        'scheduled_date' => 'datetime',
        'completed_date' => 'datetime',
        'estimated_cost' => 'decimal:2',
        'requested_date' => 'datetime',
        'actual_cost' => 'decimal:2',
        'attachments' => 'array'
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function updates(): HasMany
    {
        return $this->hasMany(MaintenanceUpdate::class);
    }

    public function updateStatus($status, $notes = null, $attachments = null)
    {
        $this->status = $status;

        if ($status === 'completed') {
            $this->completed_date = now();
        }

        $this->save();

        // Create status update record
        $this->updates()->create([
            'user_id' => Auth::id(),
            'status' => $status,
            'notes' => $notes,
            'attachments' => $attachments
        ]);

        // Notify relevant parties
        $this->notifyStatusUpdate($status, $notes);
    }

    protected function notifyStatusUpdate($status, $notes)
    {
        $notification = new MaintenanceStatusUpdateNotification($this, $status, $notes);

        // Notify tenant
        if ($this->tenant) {
            $this->tenant->notify($notification);
        }

        // Notify assigned user
        if ($this->assignedTo) {
            $this->assignedTo->notify($notification);
        }

        // Notify property manager
        $this->property->manager->notify($notification);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeAssigned($query)
    {
        return $query->where('status', 'assigned');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeOverdue($query)
    {
        return $query->where('scheduled_date', '<', now())
            ->whereNotIn('status', ['completed', 'cancelled']);
    }
}
