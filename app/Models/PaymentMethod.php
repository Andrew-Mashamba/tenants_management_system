<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'type',
        'provider',
        'account_name',
        'account_number',
        'bank_name',
        'bank_code',
        'card_last_four',
        'card_brand',
        'card_expiry',
        'mobile_number',
        'mobile_network',
        'is_default',
        'is_verified',
        'metadata'
    ];

    protected $casts = [
        'metadata' => 'array',
        'is_default' => 'boolean',
        'is_verified' => 'boolean'
    ];

    public function tenant()
    {
        return $this->belongsTo(User::class, 'tenant_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function isCard()
    {
        return $this->type === 'card';
    }

    public function isBankTransfer()
    {
        return $this->type === 'bank_transfer';
    }

    public function isMobileMoney()
    {
        return $this->type === 'mobile_money';
    }

    public function getMaskedAccountNumber()
    {
        if ($this->isCard()) {
            return "**** **** **** {$this->card_last_four}";
        }

        if ($this->isBankTransfer()) {
            return substr($this->account_number, 0, 4) . '****' . substr($this->account_number, -4);
        }

        if ($this->isMobileMoney()) {
            return substr($this->mobile_number, 0, 4) . '****' . substr($this->mobile_number, -4);
        }

        return $this->account_number;
    }
} 