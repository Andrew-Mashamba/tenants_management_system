<?php

namespace App\Livewire\Leases;

use App\Models\Lease;
use Livewire\Component;
use Livewire\WithPagination;

class LeaseList extends Component
{
    use WithPagination;

    public $search = '';
    public $status = '';
    public $sortField = 'start_date';
    public $sortDirection = 'desc';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $field;
    }

    public function render()
    {
        $query = Lease::query()
            ->with(['tenant', 'property', 'unit'])
            ->when($this->search, function ($query) {
                $query->whereHas('tenant', function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%');
                })
                ->orWhereHas('property', function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%');
                })
                ->orWhereHas('unit', function ($query) {
                    $query->where('unit_number', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })
            ->orderBy($this->sortField, $this->sortDirection);

        $leases = $query->paginate(10);

        return view('livewire.leases.lease-list', [
            'leases' => $leases
        ]);
    }
} 