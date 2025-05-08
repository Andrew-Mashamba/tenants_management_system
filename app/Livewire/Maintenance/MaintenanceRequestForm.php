<?php

namespace App\Livewire\Maintenance;

use App\Models\MaintenanceRequest;
use App\Models\Property;
use App\Models\Unit;
use App\Models\User;
use App\Models\Vendor;
use Livewire\Component;
use Livewire\WithFileUploads;

class MaintenanceRequestForm extends Component
{
    use WithFileUploads;

    public $maintenanceRequest;
    public $property_id;
    public $unit_id;
    public $tenant_id;
    public $vendor_id;
    public $title;
    public $description;
    public $priority;
    public $status;
    public $scheduled_date;
    public $estimated_cost;
    public $actual_cost;
    public $resolution_notes;
    public $attachments = [];
    public $status_notes;
    public $status_attachments = [];

    public $units = [];

    protected $rules = [
        'property_id' => 'required|exists:properties,id',
        'unit_id' => 'nullable|exists:units,id',
        'tenant_id' => 'nullable|exists:users,id',
        'vendor_id' => 'nullable|exists:vendors,id',
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'priority' => 'required|in:low,medium,high,emergency',
        'status' => 'required|in:pending,assigned,in_progress,completed,cancelled',
        'scheduled_date' => 'nullable|date',
        'estimated_cost' => 'nullable|numeric|min:0',
        'actual_cost' => 'nullable|numeric|min:0',
        'resolution_notes' => 'nullable|string',
        'attachments' => 'array',
        'attachments.*' => 'file|max:10240', // 10MB max
        'status_notes' => 'nullable|string',
        'status_attachments' => 'array',
        'status_attachments.*' => 'file|max:10240' // 10MB max
    ];

    public function mount($maintenanceRequest = null)
    {
        if ($maintenanceRequest) {
            $this->maintenanceRequest = $maintenanceRequest;
            $this->property_id = $maintenanceRequest->property_id;
            $this->unit_id = $maintenanceRequest->unit_id;
            $this->tenant_id = $maintenanceRequest->tenant_id;
            $this->vendor_id = $maintenanceRequest->vendor_id;
            $this->title = $maintenanceRequest->title;
            $this->description = $maintenanceRequest->description;
            $this->priority = $maintenanceRequest->priority;
            $this->status = $maintenanceRequest->status;
            $this->scheduled_date = $maintenanceRequest->scheduled_date?->format('Y-m-d\TH:i');
            $this->estimated_cost = $maintenanceRequest->estimated_cost;
            $this->actual_cost = $maintenanceRequest->actual_cost;
            $this->resolution_notes = $maintenanceRequest->resolution_notes;
            $this->attachments = $maintenanceRequest->attachments ?? [];
        } else {
            $this->status = 'pending';
            $this->priority = 'medium';
        }
    }

    public function updatedPropertyId($value)
    {
        $this->loadUnits();
        $this->unit_id = '';
        $this->tenant_id = '';
    }

    public function updatedUnitId($value)
    {
        if ($value) {
            $unit = Unit::find($value);
            if ($unit && $unit->tenant_id) {
                $this->tenant_id = $unit->tenant_id;
            }
        }
    }

    public function loadUnits()
    {
        if ($this->property_id) {
            $this->units = Unit::where('property_id', $this->property_id)->get();
        } else {
            $this->units = [];
        }
    }

    public function save()
    {
        $this->validate();

        $data = [
            'property_id' => $this->property_id,
            'unit_id' => $this->unit_id,
            'tenant_id' => $this->tenant_id,
            'vendor_id' => $this->vendor_id,
            'title' => $this->title,
            'description' => $this->description,
            'priority' => $this->priority,
            'status' => $this->status,
            'scheduled_date' => $this->scheduled_date,
            'estimated_cost' => $this->estimated_cost,
            'actual_cost' => $this->actual_cost,
            'resolution_notes' => $this->resolution_notes,
            'attachments' => $this->attachments
        ];

        if ($this->maintenanceRequest) {
            $this->maintenanceRequest->update($data);
            $message = 'Maintenance request updated successfully.';
        } else {
            $this->maintenanceRequest = MaintenanceRequest::create($data);
            $message = 'Maintenance request created successfully.';
        }

        // Handle status update if notes or attachments are provided
        if ($this->status_notes || count($this->status_attachments) > 0) {
            $this->maintenanceRequest->updateStatus(
                $this->status,
                $this->status_notes,
                $this->status_attachments
            );
        }

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => $message
        ]);

        return redirect()->route('maintenance-requests.show', $this->maintenanceRequest);
    }

    public function getPropertiesProperty()
    {
        return Property::all();
    }

    public function getUnitsProperty()
    {
        if (!$this->property_id) {
            return collect();
        }
        return Unit::where('property_id', $this->property_id)->get();
    }

    public function getTenantsProperty()
    {
        if (!$this->unit_id) {
            return collect();
        }
        return User::whereHas('leases', function ($query) {
            $query->where('unit_id', $this->unit_id)
                ->where('status', 'active');
        })->get();
    }

    public function getVendorsProperty()
    {
        return Vendor::active()->get();
    }

    public function render()
    {
        $properties = Property::all();
        $staff = User::whereHas('roles', function ($query) {
            $query->whereIn('slug', ['admin', 'staff', 'maintenance']);
        })->get();

        return view('livewire.maintenance.maintenance-request-form', [
            'properties' => $properties,
            'staff' => $staff,
        ]);
    }
}
