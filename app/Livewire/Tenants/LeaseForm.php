<?php

namespace App\Livewire\Tenants;

use App\Models\Lease;
use App\Models\Unit;
use App\Models\User;
use Livewire\Component;
use App\Models\LeaseTemplate;
use App\Models\Property;
use App\Models\Tenant;
use Carbon\Carbon;
use Livewire\Attributes\Title;
class LeaseForm extends Component
{
    #[Title('Lease Form')]
    public $lease;
    // public $unit_id;
    public $unit_ids = []; 
    public $tenant_id;
    public $start_date;
    public $end_date;
    public $rent_amount;
    public $deposit_amount;
    public $payment_frequency;
    public $status;
    public $terms = [];
    public $notes;
    public $lease_template;
    public $property_id;

    public $billing_amount;

    protected $rules = [
        'property_id' => 'required|exists:properties,id',
        // 'unit_id' => 'required|exists:units,id',
        'unit_ids' => 'required|array|min:1',
        'tenant_id' => 'required|exists:users,id',
        'start_date' => 'required|date|after_or_equal:today',
        'end_date' => 'required|date|after:start_date',
        'rent_amount' => 'required|numeric|min:0',
        'deposit_amount' => 'required|numeric|min:0',
        'payment_frequency' => 'required|in:monthly,quarterly,annually',
        'status' => 'required|in:active,expired,terminated',
        'terms' => 'array',
        'notes' => 'nullable|string',
        'lease_template' => 'exists:lease_templates,id',
    ];

    public function mount(Lease $lease)
    {
        if ($lease->exists) {
            $this->fill($lease->toArray());
            // Format dates for HTML date input
            $this->start_date = $lease->start_date ? $lease->start_date->format('Y-m-d') : null;
            $this->end_date = $lease->end_date ? $lease->end_date->format('Y-m-d') : null;
            $this->terms = $lease->terms ?? [];
            $this->rent_amount = $lease->rent_amount;
            $this->deposit_amount = $lease->deposit_amount;
            $this->payment_frequency = $lease->payment_frequency;
            $this->status = $lease->status;
            
            // Calculate billing amount based on payment frequency
            $frequencyMultiplier = match($lease->payment_frequency) {
                'monthly' => 1,
                'quarterly' => 3,
                'annually' => 12,
                default => 1
            };
            $this->billing_amount = number_format($lease->rent_amount / $frequencyMultiplier, 2);
            // $this->unit_ids = $lease->units->pluck('id')->toArray();
        } else {
            $this->status = 'pending';
            $this->payment_frequency = 'monthly';
        }
    }

    // public function calculateRentAmount()
    // {
    //     $unit = Unit::find($this->unit_id);
    //     $this->rent_amount = $unit->unit_price * $this->payment_frequency;
    // }   

    //calculate the total rent and show in the form
    // public function totalRentAmount()
    // {
    //     $units = Unit::find($this->unit_ids);
    //     $this->rent_amount = $units->sum('unit_price') * $this->payment_frequency;
    // }

    public function save()
    {
        $this->validate();

        $data = [
            // 'unit_id' => $this->unit_id,
            'unit_ids' => $this->unit_ids,
            'property_id' => $this->property_id,
            'tenant_id' => $this->tenant_id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'rent_amount' => $this->rent_amount,
            'deposit_amount' => $this->deposit_amount,
            'payment_frequency' => $this->payment_frequency,
            'status' => $this->status,
            'terms' => $this->terms,
            'notes' => $this->notes,
            'lease_template' => $this->lease_template,
        ];


        
        // if ($this->lease->exists) {
        if($this->lease){
            $this->lease->update($data);
            $this->updatedUnitStatus(); // update unit status based on lease status
           
            session()->flash('message', 'Lease updated successfully.');
        } else {
           
            Lease::create($data);
            $this->updatedUnitStatus(); // update unit status based on lease status
            
            session()->flash('message', 'Lease created successfully.');
        }

        return redirect()->route('leases.index');
    }


    public function updatedUnitStatus()
    {
        foreach ($this->unit_ids as $unit_id) {
            $unit = Unit::find($unit_id);
            $unit->tenant_id = $this->tenant_id;
            if($this->status == 'active'){
                $unit->status = 'occupied';
                $unit->availability_status = 'occupied';
            }
            if($this->status == 'expired' || $this->status == 'terminated'){
                $unit->status = 'available';
                $unit->availability_status = 'available';
            }
            // if($this->status == 'pending'){
            //     $unit->status = 'reserved';
            //     $unit->availability_status = 'reserved';
            // }

            $unit->save();
        }
    }


    //////rent amount calculation
    public function updatedUnitIds()
    {
        $this->calculateTotalRent();
    }

    public function updatedStartDate()
    {
        $this->calculateTotalRent();
    }

    public function updatedEndDate()
    {
        $this->calculateTotalRent();
    }

    public function updatedPaymentFrequency()
    {
        $this->calculateTotalRent();
    }

    public function updatedPropertyId()
    {
        $this->unit_ids = []; // Reset unit selection when property changes
        $this->calculateTotalRent();
    }

    public function calculateTotalRent()
    {
        if (empty($this->unit_ids) || !$this->start_date || !$this->end_date) {
            $this->rent_amount = 0;
            return;
        }

        $start = Carbon::parse($this->start_date);
        $end = Carbon::parse($this->end_date);

        $units = Unit::whereIn('id', $this->unit_ids)->get();
        $totalPerInterval = $units->sum('unit_price');

        // Calculate total months between dates
        $totalMonths = $start->diffInMonths($end);

        // Calculate intervals based on payment frequency
        $intervals = match ($this->payment_frequency) {
            'monthly' => $totalMonths,
            'quarterly' => ceil($totalMonths / 3),
            'annually' => ceil($totalMonths / 12),
            default => 1,
        };
        
        $this->rent_amount = $totalPerInterval * $totalMonths;  //total rent amount
        $this->billing_amount = $this->rent_amount / $intervals; //billing amount per interval
        
    }





    public function cancel()
    {
        return redirect()->route('leases.index');
    }

    private function getUnits()
    {
        if($this->lease){
            return Unit::whereIn('id', $this->lease->unit_ids)->get();
        }
       
        return Unit::where('status', 'available')->where('property_id', $this->property_id)->get();
    }

    public function render()
    {
        $properties = Property::all();

        $units = $this->getUnits();
        
        // $units = Unit::all();
        $lease_templates = LeaseTemplate::all();
        // $tenants = User::whereHas('roles', function ($query) {
        //     $query->where('slug', 'tenant');
        // })->get();

        $tenants = Tenant::all();

        // dd($tenants);
        return view('livewire.tenants.lease-form', [
            'units' => $units,
            'tenants' => $tenants,
            'lease_templates' => $lease_templates,
            'properties' => $properties,
        ]);
    }
}
