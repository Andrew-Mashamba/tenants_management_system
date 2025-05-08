<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class MaintenanceReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'total_requests',
        'completed_requests',
        'pending_requests',
        'overdue_requests',
        'total_cost',
        'category_breakdown',
        'vendor_breakdown',
        'response_time_metrics',
        'report_date'
    ];

    protected $casts = [
        'category_breakdown' => 'array',
        'vendor_breakdown' => 'array',
        'response_time_metrics' => 'array',
        'report_date' => 'datetime'
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function calculateMetrics(): void
    {
        // Calculate total requests
        $this->total_requests = MaintenanceRequest::whereHas('unit', function ($query) {
                $query->where('property_id', $this->property_id);
            })
            ->whereMonth('created_at', now()->month)
            ->count();

        // Calculate completed requests
        $this->completed_requests = MaintenanceRequest::whereHas('unit', function ($query) {
                $query->where('property_id', $this->property_id);
            })
            ->whereMonth('created_at', now()->month)
            ->where('status', 'completed')
            ->count();

        // Calculate pending requests
        $this->pending_requests = MaintenanceRequest::whereHas('unit', function ($query) {
                $query->where('property_id', $this->property_id);
            })
            ->whereMonth('created_at', now()->month)
            ->whereIn('status', ['pending', 'in_progress'])
            ->count();

        // Calculate overdue requests
        $this->overdue_requests = MaintenanceRequest::whereHas('unit', function ($query) {
                $query->where('property_id', $this->property_id);
            })
            ->whereMonth('created_at', now()->month)
            ->where('status', '!=', 'completed')
            ->where('scheduled_date', '<', now())
            ->count();

        // Calculate total cost
        $this->total_cost = MaintenanceRequest::whereHas('unit', function ($query) {
                $query->where('property_id', $this->property_id);
            })
            ->whereMonth('created_at', now()->month)
            ->sum('actual_cost');

        // Calculate category breakdown
        $this->category_breakdown = MaintenanceRequest::whereHas('unit', function ($query) {
                $query->where('property_id', $this->property_id);
            })
            ->whereMonth('created_at', now()->month)
            ->select('category', DB::raw('count(*) as count'), DB::raw('sum(actual_cost) as total_cost'))
            ->groupBy('category')
            ->get()
            ->toArray();

        // Calculate vendor breakdown
        $this->vendor_breakdown = MaintenanceRequest::whereHas('unit', function ($query) {
                $query->where('property_id', $this->property_id);
            })
            ->whereMonth('created_at', now()->month)
            ->select('vendor_id', DB::raw('count(*) as count'), DB::raw('sum(actual_cost) as total_cost'))
            ->groupBy('vendor_id')
            ->with('vendor:id,name')
            ->get()
            ->map(function ($request) {
                return [
                    'vendor_id' => $request->vendor_id,
                    'vendor_name' => $request->vendor->name,
                    'count' => $request->count,
                    'total_cost' => $request->total_cost
                ];
            })
            ->toArray();

        // Calculate response time metrics
        $this->response_time_metrics = MaintenanceRequest::whereHas('unit', function ($query) {
                $query->where('property_id', $this->property_id);
            })
            ->whereMonth('created_at', now()->month)
            ->where('status', 'completed')
            ->select(
                DB::raw('avg(TIMESTAMPDIFF(HOUR, created_at, completed_date)) as avg_response_time'),
                DB::raw('min(TIMESTAMPDIFF(HOUR, created_at, completed_date)) as min_response_time'),
                DB::raw('max(TIMESTAMPDIFF(HOUR, created_at, completed_date)) as max_response_time')
            )
            ->first()
            ->toArray();
    }
} 