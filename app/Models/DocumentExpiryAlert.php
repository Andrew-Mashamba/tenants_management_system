<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentExpiryAlert extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_id',
        'user_id',
        'alert_date',
        'is_sent',
    ];

    protected $casts = [
        'alert_date' => 'date',
        'is_sent' => 'boolean',
    ];

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopePending($query)
    {
        return $query->where('is_sent', false)
            ->where('alert_date', '<=', now());
    }

    public function markAsSent(): void
    {
        $this->update(['is_sent' => true]);
    }
} 