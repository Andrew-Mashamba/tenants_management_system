<?php

namespace App\Livewire\Payments;

use App\Models\PaymentMethod;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;

class PaymentMethodForm extends Component
{
    use WithFileUploads;

    public $paymentMethod;
    public $type;
    public $provider;
    public $account_name;
    public $account_number;
    public $bank_name;
    public $bank_code;
    public $card_number;
    public $card_expiry;
    public $card_cvv;
    public $mobile_number;
    public $mobile_network;
    public $is_default = false;

    protected $rules = [
        'type' => 'required|in:card,bank_transfer,mobile_money',
        'provider' => 'required|string',
        'account_name' => 'required_if:type,bank_transfer',
        'account_number' => 'required_if:type,bank_transfer',
        'bank_name' => 'required_if:type,bank_transfer',
        'bank_code' => 'required_if:type,bank_transfer',
        'card_number' => 'required_if:type,card',
        'card_expiry' => 'required_if:type,card',
        'card_cvv' => 'required_if:type,card',
        'mobile_number' => 'required_if:type,mobile_money',
        'mobile_network' => 'required_if:type,mobile_money',
        'is_default' => 'boolean'
    ];

    public function mount($paymentMethod = null)
    {
        if ($paymentMethod) {
            $this->paymentMethod = $paymentMethod;
            $this->type = $paymentMethod->type;
            $this->provider = $paymentMethod->provider;
            $this->account_name = $paymentMethod->account_name;
            $this->account_number = $paymentMethod->account_number;
            $this->bank_name = $paymentMethod->bank_name;
            $this->bank_code = $paymentMethod->bank_code;
            $this->mobile_number = $paymentMethod->mobile_number;
            $this->mobile_network = $paymentMethod->mobile_network;
            $this->is_default = $paymentMethod->is_default;
        }
    }

    public function save()
    {
        $this->validate();

        $data = [
            'tenant_id' => Auth::id(),
            'type' => $this->type,
            'provider' => $this->provider,
            'is_default' => $this->is_default
        ];

        if ($this->type === 'bank_transfer') {
            $data['account_name'] = $this->account_name;
            $data['account_number'] = $this->account_number;
            $data['bank_name'] = $this->bank_name;
            $data['bank_code'] = $this->bank_code;
        } elseif ($this->type === 'card') {
            // In a real application, you would use a payment gateway to tokenize the card
            $data['card_last_four'] = substr($this->card_number, -4);
            $data['card_brand'] = $this->getCardBrand($this->card_number);
            $data['card_expiry'] = $this->card_expiry;
        } elseif ($this->type === 'mobile_money') {
            $data['mobile_number'] = $this->mobile_number;
            $data['mobile_network'] = $this->mobile_network;
        }

        if ($this->is_default) {
            PaymentMethod::where('tenant_id', Auth::id())
                ->where('is_default', true)
                ->update(['is_default' => false]);
        }

        if ($this->paymentMethod) {
            $this->paymentMethod->update($data);
            $message = 'Payment method updated successfully.';
        } else {
            PaymentMethod::create($data);
            $message = 'Payment method added successfully.';
        }

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => $message
        ]);

        return redirect()->route('payment-methods.index');
    }

    protected function getCardBrand($number)
    {
        $number = preg_replace('/[^0-9]/', '', $number);
        
        if (preg_match('/^4[0-9]{12}(?:[0-9]{3})?$/', $number)) {
            return 'visa';
        } elseif (preg_match('/^5[1-5][0-9]{14}$/', $number)) {
            return 'mastercard';
        } elseif (preg_match('/^3[47][0-9]{13}$/', $number)) {
            return 'amex';
        } elseif (preg_match('/^6(?:011|5[0-9]{2})[0-9]{12}$/', $number)) {
            return 'discover';
        }
        
        return 'unknown';
    }

    public function render()
    {
        return view('livewire.payments.payment-method-form');
    }
} 