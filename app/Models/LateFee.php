<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class LateFee extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'amount',
        'type',
        'grace_period_days',
        'frequency_days',
        'maximum_amount',
        'is_active',
        'description'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'maximum_amount' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function charges()
    {
        return $this->hasMany(LateFeeCharge::class);
    }

    public function calculateFee($invoice)
    {
        if (!$this->is_active) {
            return 0;
        }

        $daysLate = $invoice->days_late;
        if ($daysLate <= $this->grace_period_days) {
            return 0;
        }

        $effectiveDaysLate = $daysLate - $this->grace_period_days;
        $numberOfCharges = ceil($effectiveDaysLate / $this->frequency_days);

        if ($this->type === 'fixed') {
            $fee = $this->amount * $numberOfCharges;
        } else {
            $fee = ($invoice->amount * ($this->amount / 100)) * $numberOfCharges;
        }

        if ($this->maximum_amount && $fee > $this->maximum_amount) {
            return $this->maximum_amount;
        }

        return $fee;
    }

    public function applyToInvoice($invoice)
    {
        $fee = $this->calculateFee($invoice);
        if ($fee <= 0) {
            return null;
        }

        return DB::transaction(function () use ($invoice, $fee) {
            $charge = $this->charges()->create([
                'invoice_id' => $invoice->id,
                'amount' => $fee,
                'charge_date' => now(),
                'description' => "Late fee for invoice #{$invoice->id}"
            ]);

            $invoice->update([
                'late_fee_amount' => $invoice->late_fee_amount + $fee,
                'total_amount' => $invoice->total_amount + $fee
            ]);

            return $charge;
        });
    }
} 