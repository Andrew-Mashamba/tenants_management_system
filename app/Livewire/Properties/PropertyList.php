<?php

namespace App\Livewire\Properties;

use App\Models\Property;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
class PropertyList extends Component
{
    use WithPagination;

    #[Title('Property List')]

    public $search = '';
    public $status = '';
    public $property_type = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => ''],
        'property_type' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Property::query()
            ->with(['units' => function ($query) {
                $query->select('id', 'property_id', 'type', 'status');
            }]);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('address', 'like', '%' . $this->search . '%')
                    ->orWhere('city', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->status) {
            $query->where('status', $this->status);
        }

        if ($this->property_type) {
            $query->where('property_type', $this->property_type);
        }

        $properties = $query->paginate(10);

        return view('livewire.properties.property-list', [
            'properties' => $properties,
        ]);
    }
}
