<?php

namespace App\Livewire\Tenants;

use App\Models\Lease;
use App\Models\Unit;
use App\Models\User;
use Livewire\Component;

class LeaseForm extends Component
{
    public $lease;
    public $unit_id;
    public $tenant_id;
    public $start_date;
    public $end_date;
    public $rent_amount;
    public $deposit_amount;
    public $payment_frequency;
    public $status;
    public $terms = [];
    public $notes;

    protected $rules = [
        'unit_id' => 'required|exists:units,id',
        'tenant_id' => 'required|exists:users,id',
        'start_date' => 'required|date|after_or_equal:today',
        'end_date' => 'required|date|after:start_date',
        'rent_amount' => 'required|numeric|min:0',
        'deposit_amount' => 'required|numeric|min:0',
        'payment_frequency' => 'required|in:monthly,quarterly,annually',
        'status' => 'required|in:active,expired,terminated,pending',
        'terms' => 'array',
        'notes' => 'nullable|string',
    ];

    public function mount(Lease $lease = null)
    {
        $this->lease = $lease;
        if ($lease->exists) {
            $this->fill($lease->toArray());
            $this->terms = $lease->terms ?? [];
        } else {
            $this->status = 'pending';
            $this->payment_frequency = 'monthly';
        }
    }

    public function save()
    {
        $this->validate();

        $data = [
            'unit_id' => $this->unit_id,
            'tenant_id' => $this->tenant_id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'rent_amount' => $this->rent_amount,
            'deposit_amount' => $this->deposit_amount,
            'payment_frequency' => $this->payment_frequency,
            'status' => $this->status,
            'terms' => $this->terms,
            'notes' => $this->notes,
        ];

        if ($this->lease->exists) {
            $this->lease->update($data);
            session()->flash('message', 'Lease updated successfully.');
        } else {
            Lease::create($data);
            session()->flash('message', 'Lease created successfully.');
        }

        return redirect()->route('leases.index');
    }

    public function render()
    {
        $units = Unit::where('status', 'available')->get();
        $tenants = User::whereHas('roles', function ($query) {
            $query->where('slug', 'tenant');
        })->get();

        return view('livewire.tenants.lease-form', [
            'units' => $units,
            'tenants' => $tenants,
        ]);
    }
}
