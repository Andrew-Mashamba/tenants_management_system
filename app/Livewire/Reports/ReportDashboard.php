<?php

namespace App\Livewire\Reports;

use App\Models\{
    OccupancyReport,
    RentCollectionReport,
    MaintenanceReport,
    LeaseExpiryReport,
    FinancialDashboardMetrics,
    Property
};
use Livewire\Component;
use Livewire\WithPagination;

class ReportDashboard extends Component
{
    use WithPagination;

    public $selectedProperty;
    public $dateRange = 'month';
    public $startDate;
    public $endDate;

    protected $queryString = [
        'selectedProperty' => ['except' => ''],
        'dateRange' => ['except' => 'month'],
        'startDate' => ['except' => ''],
        'endDate' => ['except' => '']
    ];

    public function mount()
    {
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate = now()->endOfMonth()->format('Y-m-d');
    }

    public function updatedDateRange($value)
    {
        switch ($value) {
            case 'month':
                $this->startDate = now()->startOfMonth()->format('Y-m-d');
                $this->endDate = now()->endOfMonth()->format('Y-m-d');
                break;
            case 'quarter':
                $this->startDate = now()->startOfQuarter()->format('Y-m-d');
                $this->endDate = now()->endOfQuarter()->format('Y-m-d');
                break;
            case 'year':
                $this->startDate = now()->startOfYear()->format('Y-m-d');
                $this->endDate = now()->endOfYear()->format('Y-m-d');
                break;
        }
    }

    public function getPropertiesProperty()
    {
        return Property::orderBy('name')->get();
    }

    public function getOccupancyReportProperty()
    {
        if (!$this->selectedProperty) {
            return null;
        }

        return OccupancyReport::where('property_id', $this->selectedProperty)
            ->whereBetween('report_date', [$this->startDate, $this->endDate])
            ->latest()
            ->first();
    }

    public function getRentCollectionReportProperty()
    {
        if (!$this->selectedProperty) {
            return null;
        }

        return RentCollectionReport::where('property_id', $this->selectedProperty)
            ->whereBetween('report_date', [$this->startDate, $this->endDate])
            ->latest()
            ->first();
    }

    public function getMaintenanceReportProperty()
    {
        if (!$this->selectedProperty) {
            return null;
        }

        return MaintenanceReport::where('property_id', $this->selectedProperty)
            ->whereBetween('report_date', [$this->startDate, $this->endDate])
            ->latest()
            ->first();
    }

    public function getLeaseExpiryReportProperty()
    {
        if (!$this->selectedProperty) {
            return null;
        }

        return LeaseExpiryReport::where('property_id', $this->selectedProperty)
            ->whereBetween('report_date', [$this->startDate, $this->endDate])
            ->latest()
            ->first();
    }

    public function getFinancialMetricsProperty()
    {
        if (!$this->selectedProperty) {
            return null;
        }

        return FinancialDashboardMetrics::where('property_id', $this->selectedProperty)
            ->whereBetween('report_date', [$this->startDate, $this->endDate])
            ->latest()
            ->first();
    }

    public function render()
    {
        return view('livewire.reports.report-dashboard', [
            'properties' => $this->properties,
            'occupancyReport' => $this->occupancyReport,
            'rentCollectionReport' => $this->rentCollectionReport,
            'maintenanceReport' => $this->maintenanceReport,
            'leaseExpiryReport' => $this->leaseExpiryReport,
            'financialMetrics' => $this->financialMetrics
        ]);
    }
} 