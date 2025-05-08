<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CoTenant extends Model
{
    use HasFactory;

    protected $fillable = [
        'lease_id',
        'primary_tenant_id',
        'name',
        'email',
        'phone',
        'relationship',
        'date_of_birth',
        'status',
        'is_adult',
        'notes',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'is_adult' => 'boolean',
    ];

    public function lease(): BelongsTo
    {
        return $this->belongsTo(Lease::class);
    }

    public function primaryTenant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'primary_tenant_id');
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function markAsInactive(): bool
    {
        return $this->update(['status' => 'inactive']);
    }

    public function markAsActive(): bool
    {
        return $this->update(['status' => 'active']);
    }
}
