<?php

namespace App\Livewire\Tenants;

use App\Models\Lease;
use App\Models\Unit;
use Livewire\Component;

class LeaseTermination extends Component
{
    public $lease;
    public $showModal = false;
    public $terminationDate;
    public $reason;

    protected $rules = [
        'terminationDate' => 'required|date|after_or_equal:today',
        'reason' => 'required|string|min:10',
    ];


    public function mount(Lease $lease)
    {
        $this->lease = $lease;
        $this->terminationDate = now()->format('Y-m-d');
    }

    public function confirmTermination()
    {
        $this->validate();
        
        // Update lease status
        $this->lease->update([
            'status' => 'terminated',
            'end_date' => $this->terminationDate,
            'notes' => $this->lease->notes . "\nTermination Reason: " . $this->reason . "\nTerminated on: " . now()->format('Y-m-d H:i:s')
        ]);
        $this->lease->save();

        // Update unit status
        foreach ($this->lease->unit_ids as $unit_id) {
            $unit = Unit::find($unit_id);
            if ($unit) {
                $unit->update([
                    'status' => 'available',
                    'availability_status' => 'available',
                    'tenant_id' => null,
                ]);
                $unit->save();
            }
        }

        // Update tenant status
        if ($this->lease->tenant) {
            // check if tenant has other leases
            $otherLeases = Lease::where('tenant_id', $this->lease->tenant_id)->where('status', '!=', 'terminated')->count();
            if ($otherLeases == 0) {
                $this->lease->tenant->update([
                    'status' => 'terminated'
                ]);
                $this->lease->tenant->save();
            }
        }

        $this->showModal = false;
        session()->flash('message', 'Lease terminated successfully.');
        return redirect()->route('leases.index');
    }

    public function render()
    {
        $units = Unit::whereIn('id', $this->lease->unit_ids)->get();
        return view('livewire.tenants.lease-termination', [
            'units' => $units
        ]);
    }
}
