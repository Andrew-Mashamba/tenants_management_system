<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'type',
        'description',
        'data',
        'generated_by',
        'generated_at',
        'parameters',
    ];

    protected $casts = [
        'data' => 'array',
        'parameters' => 'array',
        'generated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'generated_by');
    }

    public function getFormattedDataAttribute()
    {
        return json_encode($this->data, JSON_PRETTY_PRINT);
    }
} 