<?php

namespace App\Livewire\Properties;

use App\Models\Property;
use Livewire\Component;

class PropertyShow extends Component
{
    public Property $property;

    public function mount(Property $property)
    {
        $this->property = $property;
    }

    public function render()
    {
        return view('livewire.properties.property-show');
    }
}
