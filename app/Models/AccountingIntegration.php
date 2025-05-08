<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AccountingIntegration extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider',
        'api_key',
        'api_secret',
        'is_active',
        'settings',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'settings' => 'array',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function chartOfAccountsMappings(): HasMany
    {
        return $this->hasMany(ChartOfAccountsMapping::class);
    }

    public function getService()
    {
        $provider = strtolower($this->provider);
        
        switch ($provider) {
            case 'quickbooks':
                return new \App\Services\Accounting\QuickBooksService($this);
            default:
                throw new \InvalidArgumentException("Unsupported accounting provider: {$provider}");
        }
    }
} 