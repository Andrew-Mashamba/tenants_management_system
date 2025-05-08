<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LateFeeCharge extends Model
{
    use HasFactory;

    protected $fillable = [
        'late_fee_id',
        'invoice_id',
        'amount',
        'charge_date',
        'description'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'charge_date' => 'date'
    ];

    public function lateFee()
    {
        return $this->belongsTo(LateFee::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
} 