<?php

namespace App\Services;

use App\Models\Lease;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LeaseAnalyticsService
{
    /**
     * Get lease performance metrics
     *
     * @param array $filters
     * @return array
     */
    public function getLeasePerformanceMetrics(array $filters = []): array
    {
        $query = Lease::query();

        // Apply filters
        if (!empty($filters['property_id'])) {
            $query->where('property_id', $filters['property_id']);
        }
        if (!empty($filters['start_date'])) {
            $query->where('start_date', '>=', $filters['start_date']);
        }
        if (!empty($filters['end_date'])) {
            $query->where('end_date', '<=', $filters['end_date']);
        }

        $totalLeases = $query->count();
        $activeLeases = $query->where('status', 'active')->count();
        $expiredLeases = $query->where('status', 'expired')->count();
        $renewedLeases = $query->whereHas('workflows', function ($q) {
            $q->where('type', 'renewal')->where('status', 'completed');
        })->count();

        return [
            'total_leases' => $totalLeases,
            'active_leases' => $activeLeases,
            'expired_leases' => $expiredLeases,
            'renewed_leases' => $renewedLeases,
            'renewal_rate' => $totalLeases > 0 ? ($renewedLeases / $totalLeases) * 100 : 0,
        ];
    }

    /**
     * Get payment analytics
     *
     * @param array $filters
     * @return array
     */
    public function getPaymentAnalytics(array $filters = []): array
    {
        $query = Payment::query();

        // Apply filters
        if (!empty($filters['property_id'])) {
            $query->whereHas('lease', function ($q) use ($filters) {
                $q->where('property_id', $filters['property_id']);
            });
        }
        if (!empty($filters['start_date'])) {
            $query->where('paid_at', '>=', $filters['start_date']);
        }
        if (!empty($filters['end_date'])) {
            $query->where('paid_at', '<=', $filters['end_date']);
        }

        $totalPayments = $query->sum('amount');
        $onTimePayments = $query->where('is_late', false)->sum('amount');
        $latePayments = $query->where('is_late', true)->sum('amount');
        $averagePaymentTime = $query->avg('days_late');

        return [
            'total_payments' => $totalPayments,
            'on_time_payments' => $onTimePayments,
            'late_payments' => $latePayments,
            'on_time_rate' => $totalPayments > 0 ? ($onTimePayments / $totalPayments) * 100 : 0,
            'average_payment_time' => $averagePaymentTime,
        ];
    }
} 