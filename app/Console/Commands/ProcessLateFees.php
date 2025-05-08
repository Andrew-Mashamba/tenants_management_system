<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Invoice;
use Carbon\Carbon;

class ProcessLateFees extends Command
{
    protected $signature = 'payments:process-late-fees';
    protected $description = 'Process late fees for overdue invoices';

    public function handle()
    {
        $overdueInvoices = Invoice::where('status', 'unpaid')
            ->where('due_date', '<', Carbon::now())
            ->get();

        foreach ($overdueInvoices as $invoice) {
            // Add late fee logic here
            $this->info("Processing late fee for invoice {$invoice->id}");
        }

        $this->info('Late fee processing completed');
    }
} 