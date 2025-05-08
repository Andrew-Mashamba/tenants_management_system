<?php

namespace App\Services\PaymentGateways;

use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Refund;
use Stripe\Exception\ApiErrorException;

class StripeGateway extends BasePaymentGateway
{
    public function __construct($gateway)
    {
        parent::__construct($gateway);
        Stripe::setApiKey($this->getApiKey());
    }

    public function processPayment(array $data)
    {
        try {
            $paymentIntent = PaymentIntent::create([
                'amount' => $data['amount'] * 100, // Convert to cents
                'currency' => $data['currency'],
                'description' => $data['description'],
                'metadata' => $data['metadata'],
                'payment_method' => $data['payment_method_id'],
                'confirm' => true,
                'return_url' => $data['return_url'] ?? null,
            ]);

            return new class($paymentIntent) {
                protected $paymentIntent;

                public function __construct($paymentIntent)
                {
                    $this->paymentIntent = $paymentIntent;
                }

                public function isSuccessful()
                {
                    return $this->paymentIntent->status === 'succeeded';
                }

                public function getAmount()
                {
                    return $this->paymentIntent->amount / 100;
                }

                public function getCurrency()
                {
                    return $this->paymentIntent->currency;
                }

                public function getStatus()
                {
                    return $this->paymentIntent->status;
                }

                public function getTransactionId()
                {
                    return $this->paymentIntent->id;
                }

                public function getPaymentMethod()
                {
                    return 'card';
                }

                public function getMetadata()
                {
                    return $this->paymentIntent->metadata;
                }
            };
        } catch (ApiErrorException $e) {
            throw new \Exception("Stripe payment failed: " . $e->getMessage());
        }
    }

    public function verifyPayment(string $transactionId)
    {
        try {
            $paymentIntent = PaymentIntent::retrieve($transactionId);
            return $paymentIntent->status === 'succeeded';
        } catch (ApiErrorException $e) {
            throw new \Exception("Stripe payment verification failed: " . $e->getMessage());
        }
    }

    public function refundPayment(string $transactionId, float $amount = null)
    {
        try {
            $refund = Refund::create([
                'payment_intent' => $transactionId,
                'amount' => $amount ? $amount * 100 : null,
            ]);

            return $refund->status === 'succeeded';
        } catch (ApiErrorException $e) {
            throw new \Exception("Stripe refund failed: " . $e->getMessage());
        }
    }

    public function getPaymentStatus(string $transactionId)
    {
        try {
            $paymentIntent = PaymentIntent::retrieve($transactionId);
            return $paymentIntent->status;
        } catch (ApiErrorException $e) {
            throw new \Exception("Stripe payment status check failed: " . $e->getMessage());
        }
    }
} 