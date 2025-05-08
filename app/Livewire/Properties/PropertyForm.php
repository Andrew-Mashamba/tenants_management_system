<?php

namespace App\Livewire\Properties;

use App\Models\Property;
use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;

class PropertyForm extends Component
{
    use WithFileUploads;

    public $property;
    public $name;
    public $description;
    public $address;
    public $city;
    public $state;
    public $country;
    public $postal_code;
    public $landlord_id;
    public $agent_id;
    public $status;
    public $total_units;
    public $available_units;
    public $property_type;
    public $year_built;
    public $amenities = [];
    public $images = [];
    public $newImages = [];
    public $availableAmenities = [
        'parking',
        'swimming_pool',
        'gym',
        'security',
        'elevator',
        'laundry',
        'playground',
        'garden',
        'cctv',
        'wifi',
    ];
    public $settings = [];
    public $default_currency = 'USD';
    public $timezone = 'UTC';
    public $document_categories = [];

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
            'landlord_id' => 'required|exists:users,id',
            'agent_id' => 'nullable|exists:users,id',
            'status' => 'required|in:active,inactive,maintenance',
            'total_units' => 'required|integer|min:1',
            'available_units' => 'required|integer|min:0|lte:total_units',
            'property_type' => 'required|string|max:255',
            'year_built' => 'nullable|integer|min:1800|max:' . (date('Y') + 1),
            'amenities' => 'array',
            'newImages.*' => 'nullable|image|max:10240',
            'settings' => 'nullable|array',
            'default_currency' => 'required|string|size:3',
            'timezone' => 'required|string|max:255',
            'document_categories' => 'nullable|array',
            'images' => 'nullable|array',
            'images.*' => 'image|max:1024'
        ];
    }

    public function mount(Property $property = null)
    {
        if ($property->exists) {
            $this->property = $property;
            $this->name = $property->name;
            $this->description = $property->description;
            $this->address = $property->address;
            $this->city = $property->city;
            $this->state = $property->state;
            $this->country = $property->country;
            $this->postal_code = $property->postal_code;
            $this->settings = $property->settings ?? [];
            $this->default_currency = $property->default_currency;
            $this->timezone = $property->timezone;
            $this->document_categories = $property->document_categories ?? [];
            $this->images = $property->images ?? [];
        }
    }

    public function save()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'description' => $this->description,
            'address' => $this->address,
            'city' => $this->city,
            'state' => $this->state,
            'country' => $this->country,
            'postal_code' => $this->postal_code,
            'landlord_id' => $this->landlord_id,
            'agent_id' => $this->agent_id,
            'status' => $this->status,
            'total_units' => $this->total_units,
            'available_units' => $this->available_units,
            'property_type' => $this->property_type,
            'year_built' => $this->year_built,
            'amenities' => $this->amenities,
            'settings' => $this->settings,
            'default_currency' => $this->default_currency,
            'timezone' => $this->timezone,
            'document_categories' => $this->document_categories,
        ];

        if ($this->newImages) {
            $imagePaths = [];
            foreach ($this->newImages as $image) {
                $path = $image->store('properties', 'public');
                $imagePaths[] = $path;
            }
            $data['images'] = array_merge($this->images, $imagePaths);
        }

        if ($this->property->exists) {
            $this->property->update($data);
            session()->flash('message', 'Property updated successfully.');
        } else {
            $this->property = Property::create($data);
            session()->flash('message', 'Property created successfully.');
        }

        if ($this->images) {
            foreach ($this->images as $image) {
                $path = $image->store('properties/' . $this->property->id, 'public');
                $this->property->images()->create(['path' => $path]);
            }
        }

        return redirect()->route('properties.index');
    }

    public function removeImage($index)
    {
        unset($this->images[$index]);
        $this->images = array_values($this->images);
    }

    public function render()
    {
        $landlords = User::whereHas('roles', function ($query) {
            $query->where('slug', 'landlord');
        })->get();

        $agents = User::whereHas('roles', function ($query) {
            $query->where('slug', 'agent');
        })->get();

        return view('livewire.properties.property-form', [
            'landlords' => $landlords,
            'agents' => $agents,
        ]);
    }
}
