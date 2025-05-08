<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class OccupancyReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'total_units',
        'occupied_units',
        'vacant_units',
        'occupancy_rate',
        'unit_type_breakdown',
        'rental_income_breakdown',
        'report_date'
    ];

    protected $casts = [
        'unit_type_breakdown' => 'array',
        'rental_income_breakdown' => 'array',
        'report_date' => 'datetime'
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function calculateMetrics(): void
    {
        $this->occupancy_rate = $this->total_units > 0 
            ? ($this->occupied_units / $this->total_units) * 100 
            : 0;

        // Calculate unit type breakdown
        $unitTypes = Unit::where('property_id', $this->property_id)
            ->select('type', DB::raw('count(*) as count'))
            ->groupBy('type')
            ->get()
            ->pluck('count', 'type')
            ->toArray();

        $this->unit_type_breakdown = $unitTypes;

        // Calculate rental income breakdown
        $rentalIncome = Lease::whereHas('unit', function ($query) {
                $query->where('property_id', $this->property_id);
            })
            ->select('units.type', DB::raw('sum(monthly_rent) as total_rent'))
            ->join('units', 'leases.unit_id', '=', 'units.id')
            ->groupBy('units.type')
            ->get()
            ->pluck('total_rent', 'type')
            ->toArray();

        $this->rental_income_breakdown = $rentalIncome;
    }
} 