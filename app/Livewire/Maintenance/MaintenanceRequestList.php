<?php

namespace App\Livewire\Maintenance;

use App\Models\MaintenanceRequest;
use Livewire\Component;
use Livewire\WithPagination;

class MaintenanceRequestList extends Component
{
    use WithPagination;

    public $search = '';
    public $status = '';
    public $priority = '';
    public $property_id = '';
    public $start_date = '';
    public $end_date = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => ''],
        'priority' => ['except' => ''],
        'property_id' => ['except' => ''],
        'start_date' => ['except' => ''],
        'end_date' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = MaintenanceRequest::query()
            ->with(['property', 'unit', 'tenant', 'assignedTo'])
            ->when($this->search, function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%')
                    ->orWhereHas('tenant', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%');
                    });
            })
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })
            ->when($this->priority, function ($query) {
                $query->where('priority', $this->priority);
            })
            ->when($this->property_id, function ($query) {
                $query->where('property_id', $this->property_id);
            })
            ->when($this->start_date, function ($query) {
                $query->whereDate('requested_date', '>=', $this->start_date);
            })
            ->when($this->end_date, function ($query) {
                $query->whereDate('requested_date', '<=', $this->end_date);
            });

        $maintenanceRequests = $query->latest()->paginate(10);
        $properties = \App\Models\Property::all();

        return view('livewire.maintenance.maintenance-request-list', [
            'maintenanceRequests' => $maintenanceRequests,
            'properties' => $properties,
        ]);
    }
}
