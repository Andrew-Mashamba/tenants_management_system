<?php

namespace App\Services\PaymentGateways;

use App\Models\PaymentGateway;

abstract class BasePaymentGateway
{
    protected $gateway;
    protected $credentials;

    public function __construct(PaymentGateway $gateway)
    {
        $this->gateway = $gateway;
        $this->credentials = $gateway->credentials;
    }

    abstract public function processPayment(array $data);
    abstract public function verifyPayment(string $transactionId);
    abstract public function refundPayment(string $transactionId, float $amount = null);
    abstract public function getPaymentStatus(string $transactionId);

    protected function isTestMode()
    {
        return $this->gateway->is_test_mode;
    }

    protected function getApiKey()
    {
        return $this->isTestMode() 
            ? $this->credentials['test_api_key'] 
            : $this->credentials['live_api_key'];
    }

    protected function getApiSecret()
    {
        return $this->isTestMode() 
            ? $this->credentials['test_api_secret'] 
            : $this->credentials['live_api_secret'];
    }

    protected function getWebhookSecret()
    {
        return $this->isTestMode() 
            ? $this->credentials['test_webhook_secret'] 
            : $this->credentials['live_webhook_secret'];
    }
} 