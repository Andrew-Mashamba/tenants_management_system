<?php

namespace App\Livewire\Billing;

use App\Models\Invoice;
use Livewire\Component;
use Livewire\WithPagination;

class InvoiceList extends Component
{
    use WithPagination;

    public $search = '';
    public $status = '';
    public $start_date = '';
    public $end_date = '';
    public $tenant_id = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => ''],
        'start_date' => ['except' => ''],
        'end_date' => ['except' => ''],
        'tenant_id' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Invoice::query()
            ->with(['tenant', 'lease'])
            ->when($this->search, function ($query) {
                $query->where('invoice_number', 'like', '%' . $this->search . '%')
                    ->orWhereHas('tenant', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%');
                    });
            })
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })
            ->when($this->tenant_id, function ($query) {
                $query->where('tenant_id', $this->tenant_id);
            })
            ->when($this->start_date, function ($query) {
                $query->whereDate('issue_date', '>=', $this->start_date);
            })
            ->when($this->end_date, function ($query) {
                $query->whereDate('issue_date', '<=', $this->end_date);
            });

        $invoices = $query->latest()->paginate(10);

        // Load units for each lease
        foreach ($invoices as $invoice) {
            if ($invoice->lease) {
                $invoice->lease->setRelation('units', $invoice->lease->units);
            }
        }

        $tenants = \App\Models\User::whereHas('roles', function ($query) {
            $query->where('slug', 'tenant');
        })->get();

        return view('livewire.billing.invoice-list', [
            'invoices' => $invoices,
            'tenants' => $tenants,
        ]);
    }
}
