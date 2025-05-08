<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'type',
        'priority',
        'start_date',
        'end_date',
        'is_published',
        'target_audience',
        'specific_recipients',
        'created_by'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_published' => 'boolean',
        'target_audience' => 'array',
        'specific_recipients' => 'array'
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true)
            ->where(function ($query) {
                $query->whereNull('start_date')
                    ->orWhere('start_date', '<=', now());
            })
            ->where(function ($query) {
                $query->whereNull('end_date')
                    ->orWhere('end_date', '>=', now());
            });
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

    public function scopeByPriority(Builder $query, string $priority): Builder
    {
        return $query->where('priority', $priority);
    }

    public function scopeForUser(Builder $query, User $user): Builder
    {
        return $query->where(function ($query) use ($user) {
            $query->whereJsonContains('target_audience', 'all')
                ->orWhereJsonContains('target_audience', $user->role)
                ->orWhereJsonContains('specific_recipients', $user->id);
        });
    }

    public function isActive(): bool
    {
        $now = now();
        return $this->is_published
            && ($this->start_date === null || $this->start_date <= $now)
            && ($this->end_date === null || $this->end_date >= $now);
    }
} 