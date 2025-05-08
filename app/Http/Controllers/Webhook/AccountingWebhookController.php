<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Models\AccountingIntegration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AccountingWebhookController extends Controller
{
    public function handle(Request $request, string $provider)
    {
        $signature = $request->header('X-Signature');
        $payload = $request->getContent();
        
        // Find the integration based on the provider
        $integration = AccountingIntegration::where('provider', $provider)
            ->where('is_active', true)
            ->first();
            
        if (!$integration) {
            Log::warning("Received webhook for inactive or non-existent {$provider} integration");
            return response()->json(['error' => 'Integration not found'], 404);
        }
        
        // Verify webhook signature
        if (!$this->verifySignature($provider, $signature, $payload, $integration)) {
            Log::warning("Invalid webhook signature for {$provider} integration");
            return response()->json(['error' => 'Invalid signature'], 401);
        }
        
        // Process the webhook based on event type
        $eventType = $request->header('X-Event-Type');
        try {
            switch ($eventType) {
                case 'invoice.created':
                case 'invoice.updated':
                    $this->handleInvoiceEvent($request->all(), $integration);
                    break;
                    
                case 'payment.created':
                case 'payment.updated':
                    $this->handlePaymentEvent($request->all(), $integration);
                    break;
                    
                case 'account.updated':
                    $this->handleAccountEvent($request->all(), $integration);
                    break;
                    
                default:
                    Log::info("Unhandled webhook event type: {$eventType}");
            }
            
            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            Log::error("Error processing {$provider} webhook: {$e->getMessage()}");
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }
    
    protected function verifySignature(string $provider, string $signature, string $payload, AccountingIntegration $integration): bool
    {
        switch ($provider) {
            case 'quickbooks':
                return $this->verifyQuickBooksSignature($signature, $payload, $integration);
            default:
                return false;
        }
    }
    
    protected function verifyQuickBooksSignature(string $signature, string $payload, AccountingIntegration $integration): bool
    {
        $expectedSignature = hash_hmac(
            'sha256',
            $payload,
            $integration->credentials['webhook_secret'] ?? ''
        );
        
        return hash_equals($expectedSignature, $signature);
    }
    
    protected function handleInvoiceEvent(array $data, AccountingIntegration $integration)
    {
        // Update or create invoice in the system
        // This would typically involve:
        // 1. Finding or creating the tenant
        // 2. Updating the invoice status
        // 3. Recording the payment if applicable
        Log::info("Processing invoice event for {$integration->provider}", $data);
    }
    
    protected function handlePaymentEvent(array $data, AccountingIntegration $integration)
    {
        // Update payment status in the system
        // This would typically involve:
        // 1. Finding the related invoice
        // 2. Updating the payment status
        // 3. Recording the payment details
        Log::info("Processing payment event for {$integration->provider}", $data);
    }
    
    protected function handleAccountEvent(array $data, AccountingIntegration $integration)
    {
        // Update account mappings
        $service = $integration->getService();
        $service->syncAccounts();
        Log::info("Processing account event for {$integration->provider}", $data);
    }
} 