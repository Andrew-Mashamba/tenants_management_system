<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChartOfAccountsMapping extends Model
{
    use HasFactory;

    protected $fillable = [
        'accounting_integration_id',
        'local_account',
        'remote_account',
        'account_type',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function accountingIntegration(): BelongsTo
    {
        return $this->belongsTo(AccountingIntegration::class);
    }
} 