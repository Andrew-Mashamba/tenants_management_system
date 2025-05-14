<?php

namespace App\Livewire\Payments;

use App\Models\Payment;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;

class PaymentList extends Component
{
    use WithPagination;

    #[Title('Payments List')]
    public $search = '';
    public $status = '';
    public $payment_method = '';
    public $start_date = '';
    public $end_date = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => ''],
        'payment_method' => ['except' => ''],
        'start_date' => ['except' => ''],
        'end_date' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Payment::query()
            ->with(['lease', 'tenant'])
            ->when($this->search, function ($query) {
                $query->whereHas('tenant', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                })->orWhere('receipt_number', 'like', '%' . $this->search . '%');
            })
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })
            ->when($this->payment_method, function ($query) {
                $query->where('payment_method', $this->payment_method);
            })
            ->when($this->start_date, function ($query) {
                $query->whereDate('payment_date', '>=', $this->start_date);
            })
            ->when($this->end_date, function ($query) {
                $query->whereDate('payment_date', '<=', $this->end_date);
            });

        $payments = $query->latest()->paginate(10);

        return view('livewire.payments.payment-list', [
            'payments' => $payments,
        ]);
    }
}
