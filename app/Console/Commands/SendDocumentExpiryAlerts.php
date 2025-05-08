<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\LeaseDocument;
use Carbon\Carbon;

class SendDocumentExpiryAlerts extends Command
{
    protected $signature = 'documents:send-expiry-alerts';
    protected $description = 'Send alerts for expiring documents';

    public function handle()
    {
        $expiringDocuments = LeaseDocument::where('expiry_date', '<=', Carbon::now()->addDays(30))
            ->where('expiry_date', '>', Carbon::now())
            ->get();

        foreach ($expiringDocuments as $document) {
            // Add notification logic here
            $this->info("Alerting for document {$document->id} expiring on {$document->expiry_date}");
        }

        $this->info('Document expiry alerts sent');
    }
} 