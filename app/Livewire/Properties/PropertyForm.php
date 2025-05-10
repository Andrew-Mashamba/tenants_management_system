<?php

namespace App\Livewire\Properties;

use App\Models\Property;
use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Unit;
use Illuminate\Support\Facades\DB;
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
    public $property_type = '';
    public $new_unit_type = '';
    public $new_unit_status = '';
    public $new_unit_amount = '';
    public $new_unit_description = '';
    public $new_unit_amenities = [];
    public $unit_list = [];
    public $total_units = 0;
    public $available_units = 0;
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
        'air_conditioning',
        'heating',
        'balcony',
        'furnished',
        'pets_allowed',
        'storage',
        'bike_rack',
        'rooftop_access',
        'concierge',
        'business_center',
        'LUKU',
        'DAWASCO',
    ];
    public $settings = [];
    public $default_currency = 'USD';
    public $timezone = 'UTC';
    public $document_categories = [];

    protected $listeners = ['propertyTypeChanged' => 'handlePropertyTypeChange'];

    public function handlePropertyTypeChange($type)
    {
        $this->property_type = $type;
        $this->unit_list = [];
        $this->total_units = 0;
        $this->available_units = 0;
    }

    public function updatedUnitList($value, $key)
    {
        $this->calculateTotals();
    }

    public function updatedPropertyType($value)
    {
        $this->unit_list = [];
        $this->total_units = 0;
        $this->available_units = 0;
    }

    public function addUnit()
    {
        $validTypes = [];
        
        if ($this->property_type == 'residential') {
            $validTypes = ['apartment', 'house', 'condo', 'townhouse', 'villa', 'land'];
        } elseif ($this->property_type == 'commercial') {
            $validTypes = ['office', 'retail', 'industrial', 'hotel', 'other'];
        } elseif ($this->property_type == 'mixed') {
            $validTypes = ['apartment', 'house', 'condo', 'townhouse', 'villa', 'land', 'office', 'retail', 'industrial', 'hotel', 'other'];
        }

        $this->validate([
            // 'new_unit_type' => 'required|string|in:' . implode(',', $validTypes),
            'new_unit_type' => 'required|string|in:apartment,house,condo,townhouse,villa,land,office,retail,industrial,hotel,other',
            'new_unit_status' => 'required|string|in:occupied,available,maintenance,reserved',
            'new_unit_amount' => 'required|integer|min:1',
            'new_unit_description' => 'required|string|max:255',
            'new_unit_amenities' => 'nullable|array',
        ]);

        // Create individual rows for each unit number
        for ($i = 1; $i <= $this->new_unit_amount; $i++) {
            $this->unit_list[] = [
                'id' => null,
                'type' => $this->property_type,
                'unit_type' => $this->new_unit_type,
                'status' => $this->new_unit_status,
                'description' => $this->new_unit_description,
                'unit_number' => $i,
                'amenities' => $this->new_unit_amenities,
                'unit_id' => 'UN' . str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT),
            ];
        }

        $this->calculateTotals();

        // Reset the form
        $this->new_unit_type = '';
        $this->new_unit_status = '';
        $this->new_unit_amount = '';
        $this->new_unit_description = '';
        $this->new_unit_amenities = [];
    }

    public function removeUnit($index)
    {
        unset($this->unit_list[$index]);
        $this->unit_list = array_values($this->unit_list);
        $this->calculateTotals();
    }

    protected function calculateTotals()
    {
        $this->total_units = count($this->unit_list);
        $this->available_units = 0;

        if (!empty($this->unit_list)) {
            foreach ($this->unit_list as $unit) {
                if (isset($unit['status']) && $unit['status'] === 'available') {
                    $this->available_units++;
                }
            }
        }
    }

    public function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
            'landlord_id' => 'required|exists:users,id',
            'agent_id' => 'nullable|exists:users,id',
            'property_type' => 'required|in:residential,commercial,mixed',
            'total_units' => 'required|integer|min:1',
            'available_units' => 'required|integer|min:0|lte:total_units',
            'year_built' => 'nullable|integer|min:1800|max:' . (date('Y') + 1),
            'amenities' => 'array',
            'newImages.*' => 'nullable|image|max:10240',
            'settings' => 'nullable|array',
            'default_currency' => 'required|string|size:3',
            'timezone' => 'required|string|max:255',
            'document_categories' => 'nullable|array',
            'images' => 'nullable|array',
            'images.*' => 'image|max:1024',
        ];

        return $rules;
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
            $this->property_type = $property->property_type;
            $this->year_built = $property->year_built;
            $this->amenities = $property->amenities ?? [];
            $this->total_units = $property->total_units;
            $this->available_units = $property->available_units;
            $this->landlord_id = $property->landlord_id;
            $this->agent_id = $property->agent_id;
            
            // Load existing units
           
            $this->unit_list = $this->property->units->map(function ($unit) {
                return [
                    'id' => $unit->id, // critical for update
                    'type' => $unit->type,
                    'unit_type' => $unit->unit_type,
                    'status' => $unit->status,
                    'description' => $unit->description,
                    'unit_number' => $unit->unit_number,
                    'amenities' => $unit->amenities,
                    'unit_id' => $unit->unit_id, // for Livewire UI tracking only
                ];
            })->toArray();
            

            // dd($this->unit_list);
        }
    }

    public function save()
    {
        $this->validate();
    
        DB::beginTransaction();
    
        try {
            // Create or update the property
            $propertyData = [
                'name' => $this->name,
                'description' => $this->description,
                'address' => $this->address,
                'city' => $this->city,
                'state' => $this->state,
                'country' => $this->country,
                'postal_code' => $this->postal_code,
                'landlord_id' => $this->landlord_id,
                'agent_id' => $this->agent_id,
                'property_type' => $this->property_type,
                'total_units' => $this->total_units,
                'available_units' => $this->available_units,
                'year_built' => $this->year_built,
                'amenities' => $this->amenities,
                'settings' => $this->settings,
                'default_currency' => $this->default_currency,
                'timezone' => $this->timezone,
                'document_categories' => $this->document_categories,
            ];

            if ($this->property) {
                $this->property->update($propertyData);
            } else {
                $this->property = Property::create($propertyData);
            }
    
            // Keep track of existing unit IDs for deletion detection
            $existingUnitIds = [];
    
            foreach ($this->unit_list as $unitData) {
                $unitUpdateData = [
                    'property_id' => $this->property->id,
                    'type' => $unitData['type'],
                    'unit_type' => $unitData['unit_type'],
                    'name' => $unitData['type'] . '-' . $unitData['unit_number'],
                    'status' => $unitData['status'],
                    'description' => $unitData['description'],
                    'unit_number' => $unitData['unit_number'],
                    'amenities' => $unitData['amenities'],
                    'unit_id' => $unitData['unit_id'],
                ];
    
                if (!empty($unitData['id'])) {
                    // Update existing unit
                    Unit::where('id', $unitData['id'])->update($unitUpdateData);
                    $existingUnitIds[] = $unitData['id'];
                } else {
                    // Create new unit
                    $newUnit = Unit::create($unitUpdateData);
                    $existingUnitIds[] = $newUnit->id;
                }
            }
    
            // Delete removed units
            Unit::where('property_id', $this->property->id)
                ->whereNotIn('id', $existingUnitIds)
                ->delete();
    
            DB::commit();
    
            session()->flash('success', 'Property and units saved successfully.');
    
            return redirect()->route('properties.index');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error saving property: ' . $e->getMessage());
        }
    }

    public function removeImage($index)
    {
        unset($this->images[$index]);
        $this->images = array_values($this->images);
    }

    public function cancel()
    {
        if ($this->property) {
            return redirect()->route('properties.show', $this->property);
        }
        return redirect()->route('properties.index');
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
