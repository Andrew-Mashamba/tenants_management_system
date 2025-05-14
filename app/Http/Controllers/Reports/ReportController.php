<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\Property;
use App\Models\Unit;
use App\Models\Lease;
use App\Models\Payment;
use App\Models\MaintenanceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index()
    {
        $reports = Report::where('generated_by', auth()->id())
            ->latest()
            ->paginate(10);

        return view('reports.index', compact('reports'));
    }

    public function create()
    {
        return view('reports.create');
    }

    public function generate(Request $request)
    {
        $request->validate([
            'type' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'parameters' => 'nullable|array'
        ]);

        $data = $this->generateReportData(
            $request->type,
            $request->start_date,
            $request->end_date,
            $request->parameters ?? []
        );

        $report = Report::create([
            'title' => $this->generateReportTitle($request->type),
            'type' => $request->type,
            'description' => $this->generateReportDescription($request->type, $request->start_date, $request->end_date),
            'data' => $data,
            'parameters' => $request->parameters,
            'generated_by' => auth()->id(),
            'generated_at' => now(),
        ]);

        return redirect()->route('reports.show', $report)
            ->with('success', 'Report generated successfully.');
    }

    public function show(Report $report)
    {
        $this->authorize('view', $report);
        return view('reports.show', compact('report'));
    }

    public function download(Report $report)
    {
        $this->authorize('view', $report);
        
        $filename = strtolower(str_replace(' ', '_', $report->title)) . '_' . now()->format('Y-m-d') . '.pdf';
        
        $pdf = PDF::loadView('reports.pdf', compact('report'));
        return $pdf->download($filename);
    }

    protected function generateReportData($type, $startDate, $endDate, $parameters)
    {
        return match($type) {
            'occupancy' => $this->generateOccupancyReport($startDate, $endDate),
            'revenue' => $this->generateRevenueReport($startDate, $endDate),
            'maintenance' => $this->generateMaintenanceReport($startDate, $endDate),
            'default' => throw new \InvalidArgumentException('Invalid report type'),
        };
    }

    protected function generateOccupancyReport($startDate, $endDate)
    {
        $totalUnits = Unit::count();
        $occupiedUnits = Lease::where('start_date', '<=', $endDate)
            ->where(function ($query) use ($startDate) {
                $query->where('end_date', '>=', $startDate)
                    ->orWhereNull('end_date');
            })
            ->where('status', 'active')
            ->select('property_unit_id')
            ->distinct()
            ->count();

        return [
            'total_units' => $totalUnits,
            'occupied_units' => $occupiedUnits,
            'vacant_units' => $totalUnits - $occupiedUnits,
            'occupancy_rate' => $totalUnits > 0 ? round(($occupiedUnits / $totalUnits) * 100, 2) : 0,
            'period' => [
                'start' => $startDate,
                'end' => $endDate
            ]
        ];
    }

    protected function generateRevenueReport($startDate, $endDate)
    {
        $payments = Payment::whereBetween('payment_date', [$startDate, $endDate])
            ->select(
                DB::raw('SUM(amount) as total_revenue'),
                DB::raw('COUNT(*) as total_transactions'),
                DB::raw('AVG(amount) as average_payment')
            )
            ->first();

        $monthlyRevenue = Payment::whereBetween('payment_date', [$startDate, $endDate])
            ->select(
                DB::raw('DATE_FORMAT(payment_date, "%Y-%m") as month'),
                DB::raw('SUM(amount) as total')
            )
            ->groupBy('month')
            ->get();

        return [
            'total_revenue' => $payments->total_revenue ?? 0,
            'total_transactions' => $payments->total_transactions ?? 0,
            'average_payment' => $payments->average_payment ?? 0,
            'monthly_revenue' => $monthlyRevenue,
            'period' => [
                'start' => $startDate,
                'end' => $endDate
            ]
        ];
    }

    protected function generateMaintenanceReport($startDate, $endDate)
    {
        $maintenanceRequests = MaintenanceRequest::whereBetween('created_at', [$startDate, $endDate])
            ->select(
                'status',
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('status')
            ->get();

        $averageResolutionTime = MaintenanceRequest::whereBetween('created_at', [$startDate, $endDate])
            ->whereNotNull('resolved_at')
            ->select(
                DB::raw('AVG(TIMESTAMPDIFF(HOUR, created_at, resolved_at)) as avg_hours')
            )
            ->first();

        return [
            'total_requests' => $maintenanceRequests->sum('count'),
            'status_breakdown' => $maintenanceRequests,
            'average_resolution_time' => $averageResolutionTime->avg_hours ?? 0,
            'period' => [
                'start' => $startDate,
                'end' => $endDate
            ]
        ];
    }

    protected function generateReportTitle($type)
    {
        return match($type) {
            'occupancy' => 'Occupancy Report',
            'revenue' => 'Revenue Report',
            'maintenance' => 'Maintenance Report',
            'default' => 'Custom Report',
        };
    }

    protected function generateReportDescription($type, $startDate, $endDate)
    {
        $start = Carbon::parse($startDate)->format('M d, Y');
        $end = Carbon::parse($endDate)->format('M d, Y');

        return match($type) {
            'occupancy' => "Occupancy analysis from {$start} to {$end}",
            'revenue' => "Revenue analysis from {$start} to {$end}",
            'maintenance' => "Maintenance request analysis from {$start} to {$end}",
            'default' => "Custom report from {$start} to {$end}",
        };
    }
} 