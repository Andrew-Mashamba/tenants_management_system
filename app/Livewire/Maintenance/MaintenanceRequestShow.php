<?php

namespace App\Livewire\Maintenance;

use App\Models\MaintenanceRequest;
use Livewire\Component;

class MaintenanceRequestShow extends Component
{
    public MaintenanceRequest $maintenanceRequest;

    public function mount(MaintenanceRequest $maintenanceRequest)
    {
        $this->maintenanceRequest = $maintenanceRequest;
    }

    public function render()
    {
        return view('livewire.maintenance.maintenance-request-show', [
            'maintenanceRequest' => $this->maintenanceRequest
        ]);
    }
}
