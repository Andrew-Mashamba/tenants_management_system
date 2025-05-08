<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentGateway extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'credentials',
        'is_active',
        'is_test_mode',
        'supported_currencies',
        'supported_payment_methods'
    ];

    protected $casts = [
        'credentials' => 'array',
        'supported_currencies' => 'array',
        'supported_payment_methods' => 'array',
        'is_active' => 'boolean',
        'is_test_mode' => 'boolean'
    ];

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function isEnabled()
    {
        return $this->is_active;
    }

    public function isTestMode()
    {
        return $this->is_test_mode;
    }

    public function supportsPaymentMethod($method)
    {
        return in_array($method, $this->supported_payment_methods ?? []);
    }

    public function supportsCurrency($currency)
    {
        return in_array($currency, $this->supported_currencies ?? []);
    }
} 