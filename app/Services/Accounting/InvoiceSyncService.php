<?php

namespace App\Services\Accounting;

use App\Models\AccountingIntegration;
use App\Models\Invoice;
use App\Models\Tenant;
use Illuminate\Support\Facades\Log;

class InvoiceSyncService
{
    protected $integration;
    protected $accountingService;

    public function __construct(AccountingIntegration $integration)
    {
        $this->integration = $integration;
        $this->accountingService = $integration->getService();
    }

    public function syncInvoices()
    {
        try {
            $invoices = $this->accountingService->getInvoices();
            
            foreach ($invoices as $accountingInvoice) {
                $this->syncInvoice($accountingInvoice);
            }
            
            return true;
        } catch (\Exception $e) {
            Log::error("Error syncing invoices: {$e->getMessage()}");
            return false;
        }
    }

    protected function syncInvoice($accountingInvoice)
    {
        // Find or create tenant
        $tenant = $this->findOrCreateTenant($accountingInvoice);
        
        // Find or create invoice
        $invoice = Invoice::updateOrCreate(
            [
                'accounting_integration_id' => $this->integration->id,
                'external_id' => $accountingInvoice->id,
            ],
            [
                'tenant_id' => $tenant->id,
                'property_id' => $this->integration->property_id,
                'amount' => $accountingInvoice->amount,
                'due_date' => $accountingInvoice->due_date,
                'status' => $this->mapStatus($accountingInvoice->status),
                'description' => $accountingInvoice->description,
                'metadata' => [
                    'accounting_data' => $accountingInvoice->toArray(),
                ],
            ]
        );
        
        // Sync invoice items
        $this->syncInvoiceItems($invoice, $accountingInvoice);
        
        // Sync payments if any
        if ($accountingInvoice->payments) {
            $this->syncPayments($invoice, $accountingInvoice->payments);
        }
    }

    protected function findOrCreateTenant($accountingInvoice)
    {
        // Try to find tenant by email or other identifier
        $tenant = Tenant::where('email', $accountingInvoice->customer->email)
            ->where('property_id', $this->integration->property_id)
            ->first();
            
        if (!$tenant) {
            $tenant = Tenant::create([
                'property_id' => $this->integration->property_id,
                'name' => $accountingInvoice->customer->name,
                'email' => $accountingInvoice->customer->email,
                'phone' => $accountingInvoice->customer->phone,
                'metadata' => [
                    'accounting_data' => $accountingInvoice->customer->toArray(),
                ],
            ]);
        }
        
        return $tenant;
    }

    protected function syncInvoiceItems(Invoice $invoice, $accountingInvoice)
    {
        $invoice->items()->delete();
        
        foreach ($accountingInvoice->items as $item) {
            $invoice->items()->create([
                'description' => $item->description,
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
                'amount' => $item->amount,
                'account_code' => $item->account_code,
            ]);
        }
    }

    protected function syncPayments(Invoice $invoice, $payments)
    {
        foreach ($payments as $payment) {
            $invoice->payments()->updateOrCreate(
                [
                    'external_id' => $payment->id,
                ],
                [
                    'amount' => $payment->amount,
                    'payment_date' => $payment->date,
                    'payment_method' => $payment->method,
                    'status' => $this->mapPaymentStatus($payment->status),
                    'metadata' => [
                        'accounting_data' => $payment->toArray(),
                    ],
                ]
            );
        }
    }

    protected function mapStatus($accountingStatus)
    {
        $mapping = [
            'DRAFT' => 'draft',
            'SENT' => 'sent',
            'PAID' => 'paid',
            'VOIDED' => 'voided',
            'DELETED' => 'deleted',
        ];
        
        return $mapping[$accountingStatus] ?? 'draft';
    }

    protected function mapPaymentStatus($accountingStatus)
    {
        $mapping = [
            'AUTHORIZED' => 'authorized',
            'CAPTURED' => 'captured',
            'REFUNDED' => 'refunded',
            'FAILED' => 'failed',
        ];
        
        return $mapping[$accountingStatus] ?? 'pending';
    }
} 