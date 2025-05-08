<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VendorService extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'service_name',
        'description',
        'hourly_rate',
        'minimum_hours',
        'is_active',
    ];

    protected $casts = [
        'hourly_rate' => 'decimal:2',
        'minimum_hours' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }
} 