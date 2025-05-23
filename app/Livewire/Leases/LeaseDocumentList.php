<?php

namespace App\Livewire\Leases;

use App\Models\Lease;
use Livewire\Component;

class LeaseDocumentList extends Component
{
    public Lease $lease;
    public $search = '';
    public $type = '';

    public function mount(Lease $lease)
    {
        $this->lease = $lease;
    }

    public function render()
    {
        $documents = $this->lease->documents()
            ->when($this->search, function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->when($this->type, function ($query) {
                $query->where('type', $this->type);
            })
            ->latest()
            ->paginate(10);

        return view('livewire.leases.lease-document-list', [
            'documents' => $documents
        ]);
    }
}
