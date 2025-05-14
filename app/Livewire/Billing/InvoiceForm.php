<?php

namespace App\Livewire\Billing;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Lease;
use App\Models\User;
use App\Models\Unit;
use Illuminate\Support\Str;
use Livewire\Component;

class InvoiceForm extends Component
{
    public $invoice;
    public $lease_id;
    public $tenant_id;
    public $invoice_number;
    public $issue_date;
    public $due_date;
    public $status = 'draft';
    public $notes;

    public $items = [];

    protected $rules = [
        'lease_id' => 'required|exists:leases,id',
        'tenant_id' => 'required|exists:users,id',
        'invoice_number' => 'required|string|unique:invoices,invoice_number',
        'issue_date' => 'required|date',
        'due_date' => 'required|date|after_or_equal:issue_date',
        'status' => 'required|in:draft,pending,paid,overdue,cancelled',
        'notes' => 'nullable|string',
        'items' => 'required|array|min:1',
        'items.*.description' => 'required|string',
        'items.*.type' => 'required|in:rent,utility,maintenance,deposit,fee,other',
        'items.*.quantity' => 'required|numeric|min:0',
        'items.*.unit_price' => 'required|numeric|min:0',
        'items.*.tax_rate' => 'required|numeric|min:0|max:100',
        // 'items.*.unit_id' => 'required|exists:units,id',

    ];

    public function mount(Invoice $invoice = null)
    {
        $this->invoice = $invoice;
        if ($invoice->exists) {
            $this->fill($invoice->toArray());
            $this->items = $invoice->items->toArray();
            $this->issue_date = $invoice->issue_date->format('Y-m-d');
            $this->due_date = $invoice->due_date->format('Y-m-d');
        } else {
            $this->issue_date = now()->format('Y-m-d');
            $this->due_date = now()->addDays(30)->format('Y-m-d');
            $this->invoice_number = 'INV-' . strtoupper(Str::random(8));
            $this->addItem();
        }
    }

    public function updatedLeaseId($value)
    {
        if ($value) {
            $lease = Lease::find($value);
            if ($lease) {
                $this->tenant_id = $lease->tenant_id;
                $this->addRentItem($lease);
            }
        }
    }

    public function addItem()
    {
        $this->items[] = [
            'description' => '',
            'type' => 'other',
            'quantity' => 1,
            'unit_price' => 0,
            'total' => 0,
            'tax_rate' => 0,
            'tax_amount' => 0,
        ];
    }

    public function addRentItem($lease)
    {
        foreach ($lease->unit_ids as $unitId) {
            $unit = Unit::find($unitId);
            if ($unit) {
                $this->items[] = [
                    'description' => 'Monthly Rent for ' . $unit->property->name . ' - Unit ' . $unit->unit_number,
                    'type' => 'rent',
                    'quantity' => 1,
                    'unit_price' => $lease->rent_amount / count($lease->unit_ids),
                    'total' => $lease->rent_amount / count($lease->unit_ids),
                    'tax_rate' => 0,
                    'tax_amount' => 0,
                    // 'unit_id' => $unitId,
                ];
            }
        }
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    public function calculateTotals()
    {
        foreach ($this->items as &$item) {
            $item['total'] = $item['quantity'] * $item['unit_price'];
            $item['tax_amount'] = $item['total'] * ($item['tax_rate'] / 100);
        }
    }

    public function save()
    {
        $this->calculateTotals();
        $this->validate();

        $total_amount = collect($this->items)->sum(function ($item) {
            return $item['total'] + $item['tax_amount'];
        });

        $data = [
            'lease_id' => $this->lease_id,
            'tenant_id' => $this->tenant_id,
            'invoice_number' => $this->invoice_number,
            'issue_date' => $this->issue_date,
            'due_date' => $this->due_date,
            'total_amount' => $total_amount,
            'paid_amount' => 0,
            'balance' => $total_amount,
            'status' => $this->status,
            'notes' => $this->notes,
        ];

        // dd($data);

        if ($this->invoice->exists) {
            $this->invoice->update($data);
            $this->invoice->items()->delete();
            session()->flash('message', 'Invoice updated successfully.');
        } else {
            $this->invoice = Invoice::create($data);
            session()->flash('message', 'Invoice created successfully.');
        }

        foreach ($this->items as $item) {
            $this->invoice->items()->create($item);
        }

        return redirect()->route('invoices.show', $this->invoice);
    }

    public function cancel()
    {
        return redirect()->route('billing.index');
    }

    public function render()
    {
        $leases = Lease::where('status', 'active')->get();
        
        // Get all unit IDs from all leases
        $unitIds = collect();
        foreach ($leases as $lease) {
            if ($lease->unit_ids) {
                $unitIds = $unitIds->merge($lease->unit_ids);
            }
        }

        // Get all units in one query
        $units = Unit::whereIn('id', $unitIds->unique())->get();

        return view('livewire.billing.invoice-form', [
            'leases' => $leases,
            'units' => $units,
        ]);
    }
}
