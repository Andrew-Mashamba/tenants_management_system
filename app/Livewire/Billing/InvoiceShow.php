<?php

namespace App\Livewire\Billing;

use App\Models\Invoice;
use Livewire\Component;

class InvoiceShow extends Component
{
    public Invoice $invoice;

    public function mount(Invoice $invoice)
    {
        $this->invoice = $invoice->load(['tenant', 'lease.unit.property', 'items']);
    }

    public function render()
    {
        return view('livewire.billing.invoice-show');
    }
}
