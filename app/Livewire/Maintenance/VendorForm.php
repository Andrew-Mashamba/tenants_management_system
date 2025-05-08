<?php

namespace App\Livewire\Maintenance;

use App\Models\Vendor;
use Livewire\Component;
use Livewire\WithFileUploads;

class VendorForm extends Component
{
    use WithFileUploads;

    public $vendor;
    public $name;
    public $email;
    public $phone;
    public $company_name;
    public $address;
    public $city;
    public $state;
    public $country;
    public $postal_code;
    public $tax_id;
    public $specialization;
    public $service_areas = [];
    public $certifications = [];
    public $hourly_rate;
    public $is_active = true;
    public $notes;
    public $new_service_name;
    public $new_service_description;
    public $new_service_price;
    public $new_service_unit;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:vendors,email',
        'phone' => 'nullable|string|max:20',
        'company_name' => 'nullable|string|max:255',
        'address' => 'nullable|string|max:255',
        'city' => 'nullable|string|max:100',
        'state' => 'nullable|string|max:100',
        'country' => 'nullable|string|max:100',
        'postal_code' => 'nullable|string|max:20',
        'tax_id' => 'nullable|string|max:50',
        'specialization' => 'nullable|string|max:100',
        'service_areas' => 'array',
        'certifications' => 'array',
        'hourly_rate' => 'nullable|numeric|min:0',
        'is_active' => 'boolean',
        'notes' => 'nullable|string',
        'new_service_name' => 'required_with:new_service_price|string|max:255',
        'new_service_description' => 'nullable|string',
        'new_service_price' => 'nullable|numeric|min:0',
        'new_service_unit' => 'required_with:new_service_price|in:hour,day,fixed'
    ];

    public function mount($vendor = null)
    {
        if ($vendor) {
            $this->vendor = $vendor;
            $this->name = $vendor->name;
            $this->email = $vendor->email;
            $this->phone = $vendor->phone;
            $this->company_name = $vendor->company_name;
            $this->address = $vendor->address;
            $this->city = $vendor->city;
            $this->state = $vendor->state;
            $this->country = $vendor->country;
            $this->postal_code = $vendor->postal_code;
            $this->tax_id = $vendor->tax_id;
            $this->specialization = $vendor->specialization;
            $this->service_areas = $vendor->service_areas ?? [];
            $this->certifications = $vendor->certifications ?? [];
            $this->hourly_rate = $vendor->hourly_rate;
            $this->is_active = $vendor->is_active;
            $this->notes = $vendor->notes;
        }
    }

    public function addServiceArea()
    {
        $this->validateOnly('service_areas');
        $this->service_areas[] = '';
    }

    public function removeServiceArea($index)
    {
        unset($this->service_areas[$index]);
        $this->service_areas = array_values($this->service_areas);
    }

    public function addCertification()
    {
        $this->validateOnly('certifications');
        $this->certifications[] = '';
    }

    public function removeCertification($index)
    {
        unset($this->certifications[$index]);
        $this->certifications = array_values($this->certifications);
    }

    public function addService()
    {
        $this->validateOnly([
            'new_service_name',
            'new_service_description',
            'new_service_price',
            'new_service_unit'
        ]);

        if ($this->vendor) {
            $this->vendor->services()->create([
                'service_name' => $this->new_service_name,
                'description' => $this->new_service_description,
                'base_price' => $this->new_service_price,
                'unit' => $this->new_service_unit
            ]);

            $this->reset(['new_service_name', 'new_service_description', 'new_service_price', 'new_service_unit']);
            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'Service added successfully.'
            ]);
        }
    }

    public function save()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'company_name' => $this->company_name,
            'address' => $this->address,
            'city' => $this->city,
            'state' => $this->state,
            'country' => $this->country,
            'postal_code' => $this->postal_code,
            'tax_id' => $this->tax_id,
            'specialization' => $this->specialization,
            'service_areas' => $this->service_areas,
            'certifications' => $this->certifications,
            'hourly_rate' => $this->hourly_rate,
            'is_active' => $this->is_active,
            'notes' => $this->notes
        ];

        if ($this->vendor) {
            $this->vendor->update($data);
            $message = 'Vendor updated successfully.';
        } else {
            Vendor::create($data);
            $message = 'Vendor created successfully.';
        }

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => $message
        ]);

        return redirect()->route('vendors.index');
    }

    public function render()
    {
        return view('livewire.maintenance.vendor-form');
    }
} 