<?php

namespace App\Livewire\Tenants;

use App\Models\CoTenant;
use Livewire\Component;

class CoTenantForm extends Component
{
    public $lease;
    public $coTenant;
    public $name;
    public $email;
    public $phone;
    public $relationship;
    public $date_of_birth;
    public $is_adult = true;
    public $notes;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'nullable|email|max:255',
        'phone' => 'nullable|string|max:20',
        'relationship' => 'required|in:spouse,child,parent,sibling,other',
        'date_of_birth' => 'required|date|before:today',
        'is_adult' => 'boolean',
        'notes' => 'nullable|string',
    ];

    public function mount($lease, CoTenant $coTenant = null)
    {
        $this->lease = $lease;
        $this->coTenant = $coTenant;

        if ($coTenant->exists) {
            $this->name = $coTenant->name;
            $this->email = $coTenant->email;
            $this->phone = $coTenant->phone;
            $this->relationship = $coTenant->relationship;
            $this->date_of_birth = $coTenant->date_of_birth?->format('Y-m-d');
            $this->is_adult = $coTenant->is_adult;
            $this->notes = $coTenant->notes;
        }
    }

    public function save()
    {
        $this->validate();

        $data = [
            'lease_id' => $this->lease->id,
            'primary_tenant_id' => $this->lease->tenant_id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'relationship' => $this->relationship,
            'date_of_birth' => $this->date_of_birth,
            'is_adult' => $this->is_adult,
            'notes' => $this->notes,
            'status' => 'active',
        ];

        if ($this->coTenant->exists) {
            $this->coTenant->update($data);
            session()->flash('message', 'Co-tenant updated successfully.');
        } else {
            CoTenant::create($data);
            session()->flash('message', 'Co-tenant added successfully.');
        }

        return redirect()->route('lease.show', $this->lease);
    }

    public function render()
    {
        return view('livewire.tenants.co-tenant-form', [
            'relationships' => [
                'spouse' => 'Spouse',
                'child' => 'Child',
                'parent' => 'Parent',
                'sibling' => 'Sibling',
                'other' => 'Other',
            ],
        ]);
    }
}
