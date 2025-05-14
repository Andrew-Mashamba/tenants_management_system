<?php

namespace App\Livewire\Payments;

use Livewire\Component;
use App\Models\Payment;
use App\Models\Lease;
class PaymentShow extends Component
{
    public $payment;

    public function mount(Payment $payment)
    {
        $this->payment = $payment;
    }

    public function getLease()
    {
        return Lease::find($this->payment->lease_id);
    }

    public function render()
    {
        return view('livewire.payments.payment-show');
    }
}
