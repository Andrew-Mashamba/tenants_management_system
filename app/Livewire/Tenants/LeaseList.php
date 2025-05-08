<?php

namespace App\Livewire\Tenants;

use App\Models\Lease;
use Livewire\Component;
use Livewire\WithPagination;

class LeaseList extends Component
{
    use WithPagination;

    public $search = '';
    public $status = '';
    public $payment_frequency = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => ''],
        'payment_frequency' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Lease::query()
            ->with(['unit', 'tenant'])
            ->when($this->search, function ($query) {
                $query->whereHas('tenant', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                })->orWhereHas('unit', function ($q) {
                    $q->where('unit_number', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })
            ->when($this->payment_frequency, function ($query) {
                $query->where('payment_frequency', $this->payment_frequency);
            });

        $leases = $query->paginate(10);

        return view('livewire.tenants.lease-list', [
            'leases' => $leases,
        ]);
    }
}
