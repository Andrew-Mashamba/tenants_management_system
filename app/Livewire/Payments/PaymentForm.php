<?php

namespace App\Livewire\Payments;

use App\Models\Payment;
use App\Models\Lease;
use App\Models\User;
use App\Models\Unit;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\Attributes\Title;

class PaymentForm extends Component
{
    #[Title('Payment Form')]
    public $payment;
    public $lease_id;
    public $tenant_id;
    public $amount;
    public $payment_date;
    public $payment_method;
    public $transaction_id;
    public $status;
    public $notes;
    public $receipt_number;

    protected $rules = [
        'lease_id' => 'required|exists:leases,id',
        'tenant_id' => 'required|exists:users,id',
        'amount' => 'required|numeric|min:0',
        'payment_date' => 'required|date',
        'payment_method' => 'required|in:cash,bank_transfer,credit_card,debit_card,check,online_payment',
        'transaction_id' => 'nullable|string|max:255',
        'status' => 'required|in:pending,completed,failed,refunded',
        'notes' => 'nullable|string',
    ];

    public function mount(Payment $payment = null)
    {
        $this->payment = $payment;
        if ($payment->exists) {
            $this->fill($payment->toArray());
        } else {
            $this->payment_date = now()->format('Y-m-d');
            $this->status = 'pending';
            $this->payment_method = 'bank_transfer';
            $this->receipt_number = 'PAY-' . strtoupper(Str::random(8));
        }
    }

    public function updatedLeaseId($value)
    {
        if ($value) {
            $lease = Lease::find($value);
            if ($lease) {
                $this->tenant_id = $lease->tenant_id;
                $this->amount = $lease->rent_amount;
            }
        }
    }

    public function save()
    {
        $this->validate();

        $data = [
            'lease_id' => $this->lease_id,
            'tenant_id' => $this->tenant_id,
            'amount' => $this->amount,
            'payment_date' => $this->payment_date,
            'payment_method' => $this->payment_method,
            'transaction_id' => $this->transaction_id,
            'status' => $this->status,
            'notes' => $this->notes,
            'receipt_number' => $this->receipt_number,
        ];

        if ($this->payment->exists) {
            $this->payment->update($data);
            session()->flash('message', 'Payment updated successfully.');
        } else {
            Payment::create($data);
            session()->flash('message', 'Payment created successfully.');
        }

        return redirect()->route('payments.index');
    }

    public function cancel()
    {
        $this->reset();
        return redirect()->route('payments.index');
    }

    public function render()
    {
        $leases = Lease::where('status', 'active')->get();
            
        $unitsIds = $leases->pluck('unit_ids')->flatten()->unique();
        $units = Unit::whereIn('id', $unitsIds)->get();
        $tenants = User::whereHas('roles', function ($query) {
            $query->where('slug', 'tenant');
        })->get();

        return view('livewire.payments.payment-form', [
            'leases' => $leases,
            'tenants' => $tenants,
            'units' => $units,
        ]);
    }
}
