<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TenantKycVerification extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'status',
        'id_type',
        'id_number',
        'id_expiry_date',
        'id_document_path',
        'proof_of_income_path',
        'employment_verification_path',
        'background_check_reference',
        'verification_notes',
        'verified_by',
        'verified_at',
    ];

    protected $casts = [
        'id_expiry_date' => 'date',
        'verified_at' => 'datetime',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isVerified(): bool
    {
        return $this->status === 'verified';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function markAsVerified(User $verifier, string $notes = null): bool
    {
        return $this->update([
            'status' => 'verified',
            'verified_by' => $verifier->id,
            'verified_at' => now(),
            'verification_notes' => $notes,
        ]);
    }

    public function markAsRejected(User $verifier, string $notes): bool
    {
        return $this->update([
            'status' => 'rejected',
            'verified_by' => $verifier->id,
            'verified_at' => now(),
            'verification_notes' => $notes,
        ]);
    }
}
