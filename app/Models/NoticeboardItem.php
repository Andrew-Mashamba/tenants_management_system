<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class NoticeboardItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'type',
        'start_date',
        'end_date',
        'is_pinned',
        'property_id',
        'created_by'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_pinned' => 'boolean'
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where(function ($query) {
            $query->whereNull('start_date')
                ->orWhere('start_date', '<=', now());
        })
        ->where(function ($query) {
            $query->whereNull('end_date')
                ->orWhere('end_date', '>=', now());
        });
    }

    public function scopeByType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    public function scopePinned(Builder $query): Builder
    {
        return $query->where('is_pinned', true);
    }

    public function scopeForProperty(Builder $query, ?Property $property): Builder
    {
        if (!$property) {
            return $query->whereNull('property_id');
        }
        return $query->where('property_id', $property->id);
    }

    public function isActive(): bool
    {
        $now = now();
        return ($this->start_date === null || $this->start_date <= $now)
            && ($this->end_date === null || $this->end_date >= $now);
    }
} 