<?php

namespace App\Services\Accounting;

interface AccountingServiceInterface
{
    public function getInvoices();
    public function getPayments();
    public function syncInvoice($invoice);
    public function syncPayment($payment);
} 