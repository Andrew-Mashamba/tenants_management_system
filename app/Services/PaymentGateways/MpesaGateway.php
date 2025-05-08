<?php

namespace App\Services\PaymentGateways;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MpesaGateway extends BasePaymentGateway
{
    protected $baseUrl;
    protected $consumerKey;
    protected $consumerSecret;
    protected $passkey;
    protected $shortcode;
    protected $callbackUrl;

    public function __construct($gateway)
    {
        parent::__construct($gateway);
        
        $this->baseUrl = $this->isTestMode() 
            ? 'https://sandbox.safaricom.co.ke' 
            : 'https://api.safaricom.co.ke';

        $this->consumerKey = $this->credentials['consumer_key'];
        $this->consumerSecret = $this->credentials['consumer_secret'];
        $this->passkey = $this->credentials['passkey'];
        $this->shortcode = $this->credentials['shortcode'];
        $this->callbackUrl = $this->credentials['callback_url'];
    }

    public function processPayment(array $data)
    {
        try {
            $timestamp = date('YmdHis');
            $password = base64_encode($this->shortcode . $this->passkey . $timestamp);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->getAccessToken(),
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/mpesa/stkpush/v1/processrequest', [
                'BusinessShortCode' => $this->shortcode,
                'Password' => $password,
                'Timestamp' => $timestamp,
                'TransactionType' => 'CustomerPayBillOnline',
                'Amount' => $data['amount'],
                'PartyA' => $data['phone_number'],
                'PartyB' => $this->shortcode,
                'PhoneNumber' => $data['phone_number'],
                'CallBackURL' => $this->callbackUrl,
                'AccountReference' => $data['metadata']['invoice_id'],
                'TransactionDesc' => $data['description']
            ]);

            if ($response->successful()) {
                $result = $response->json();
                
                return new class($result) {
                    protected $result;

                    public function __construct($result)
                    {
                        $this->result = $result;
                    }

                    public function isSuccessful()
                    {
                        return $this->result['ResponseCode'] === '0';
                    }

                    public function getAmount()
                    {
                        return $this->result['Amount'];
                    }

                    public function getCurrency()
                    {
                        return 'KES';
                    }

                    public function getStatus()
                    {
                        return $this->result['ResponseCode'] === '0' ? 'pending' : 'failed';
                    }

                    public function getTransactionId()
                    {
                        return $this->result['CheckoutRequestID'];
                    }

                    public function getPaymentMethod()
                    {
                        return 'mobile_money';
                    }

                    public function getMetadata()
                    {
                        return [
                            'merchant_request_id' => $this->result['MerchantRequestID'],
                            'checkout_request_id' => $this->result['CheckoutRequestID'],
                            'response_code' => $this->result['ResponseCode'],
                            'response_description' => $this->result['ResponseDescription'],
                            'customer_message' => $this->result['CustomerMessage']
                        ];
                    }
                };
            }

            throw new \Exception("M-Pesa payment request failed: " . $response->body());
        } catch (\Exception $e) {
            Log::error('M-Pesa payment failed: ' . $e->getMessage());
            throw $e;
        }
    }

    public function verifyPayment(string $transactionId)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->getAccessToken(),
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/mpesa/stkpushquery/v1/query', [
                'BusinessShortCode' => $this->shortcode,
                'Password' => base64_encode($this->shortcode . $this->passkey . date('YmdHis')),
                'Timestamp' => date('YmdHis'),
                'CheckoutRequestID' => $transactionId
            ]);

            if ($response->successful()) {
                $result = $response->json();
                return $result['ResultCode'] === '0';
            }

            return false;
        } catch (\Exception $e) {
            Log::error('M-Pesa payment verification failed: ' . $e->getMessage());
            return false;
        }
    }

    public function refundPayment(string $transactionId, float $amount = null)
    {
        // M-Pesa doesn't support direct refunds
        throw new \Exception("M-Pesa refunds are not supported directly. Please contact support.");
    }

    public function getPaymentStatus(string $transactionId)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->getAccessToken(),
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/mpesa/stkpushquery/v1/query', [
                'BusinessShortCode' => $this->shortcode,
                'Password' => base64_encode($this->shortcode . $this->passkey . date('YmdHis')),
                'Timestamp' => date('YmdHis'),
                'CheckoutRequestID' => $transactionId
            ]);

            if ($response->successful()) {
                $result = $response->json();
                return $result['ResultCode'] === '0' ? 'completed' : 'failed';
            }

            return 'failed';
        } catch (\Exception $e) {
            Log::error('M-Pesa payment status check failed: ' . $e->getMessage());
            return 'failed';
        }
    }

    protected function getAccessToken()
    {
        $response = Http::withBasicAuth($this->consumerKey, $this->consumerSecret)
            ->get($this->baseUrl . '/oauth/v1/generate?grant_type=client_credentials');

        if ($response->successful()) {
            return $response->json()['access_token'];
        }

        throw new \Exception("Failed to get M-Pesa access token");
    }
} 