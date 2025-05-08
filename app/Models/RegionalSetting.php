<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegionalSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'timezone',
        'date_format',
        'time_format',
        'currency',
        'language',
        'number_format',
        'first_day_of_week',
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function formatDate($date): string
    {
        if (!$date) return '';
        
        if (!$date instanceof Carbon) {
            $date = Carbon::parse($date);
        }

        return $date->format($this->date_format);
    }

    public function formatTime($time): string
    {
        if (!$time) return '';
        
        if (!$time instanceof Carbon) {
            $time = Carbon::parse($time);
        }

        return $time->format($this->time_format);
    }

    public function formatDateTime($datetime): string
    {
        if (!$datetime) return '';
        
        if (!$datetime instanceof Carbon) {
            $datetime = Carbon::parse($datetime);
        }

        return $datetime->format($this->date_format . ' ' . $this->time_format);
    }

    public function formatNumber($number, int $decimals = 2): string
    {
        $format = $this->number_format;
        $parts = explode('.', (string)$number);
        
        $integer = $parts[0];
        $decimal = $parts[1] ?? '';

        // Format integer part
        if (strpos($format, ',') !== false) {
            $integer = number_format($integer, 0, '', ',');
        }

        // Format decimal part
        if ($decimals > 0) {
            $decimal = substr(str_pad($decimal, $decimals, '0'), 0, $decimals);
            return $integer . '.' . $decimal;
        }

        return $integer;
    }

    public function formatCurrency($amount): string
    {
        $formattedAmount = $this->formatNumber($amount);
        
        switch ($this->currency) {
            case 'USD':
                return '$' . $formattedAmount;
            case 'EUR':
                return '€' . $formattedAmount;
            case 'GBP':
                return '£' . $formattedAmount;
            default:
                return $formattedAmount . ' ' . $this->currency;
        }
    }

    public function getWeekStartsAt(): int
    {
        return match ($this->first_day_of_week) {
            'monday' => 1,
            'tuesday' => 2,
            'wednesday' => 3,
            'thursday' => 4,
            'friday' => 5,
            'saturday' => 6,
            default => 0, // sunday
        };
    }
} 