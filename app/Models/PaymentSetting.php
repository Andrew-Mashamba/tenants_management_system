<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'payment_gateway',
        'gateway_credentials',
        'late_fee_percentage',
        'grace_period_days',
        'auto_charge_late_fees',
        'send_payment_reminders',
        'reminder_days_before',
    ];

    protected $casts = [
        'gateway_credentials' => 'array',
        'late_fee_percentage' => 'decimal:2',
        'auto_charge_late_fees' => 'boolean',
        'send_payment_reminders' => 'boolean',
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function getGatewayCredentialsAttribute($value)
    {
        return $value ? json_decode($value, true) : [];
    }

    public function setGatewayCredentialsAttribute($value)
    {
        $this->attributes['gateway_credentials'] = json_encode($value);
    }

    public function calculateLateFee($amount): float
    {
        return $amount * ($this->late_fee_percentage / 100);
    }

    public function isPaymentLate($dueDate): bool
    {
        return now()->diffInDays($dueDate, false) < -$this->grace_period_days;
    }
} 