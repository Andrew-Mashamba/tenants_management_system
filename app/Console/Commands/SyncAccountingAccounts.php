<?php

namespace App\Console\Commands;

use App\Models\AccountingIntegration;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SyncAccountingAccounts extends Command
{
    protected $signature = 'accounting:sync-accounts';
    protected $description = 'Synchronize accounting accounts with external providers';

    public function handle()
    {
        $integrations = AccountingIntegration::where('is_active', true)->get();
        
        if ($integrations->isEmpty()) {
            $this->info('No active accounting integrations found.');
            return self::SUCCESS;
        }

        $this->info("Processing {$integrations->count()} integrations...");

        foreach ($integrations as $integration) {
            try {
                $this->info("Syncing accounts for {$integration->provider} integration...");
                
                $service = $integration->getService();
                if ($service->syncAccounts()) {
                    $integration->update(['last_sync_at' => now()]);
                    $this->info("Successfully synced accounts for {$integration->provider}.");
                } else {
                    $this->error("Failed to sync accounts for {$integration->provider}.");
                }
            } catch (\Exception $e) {
                $this->error("Error syncing {$integration->provider}: {$e->getMessage()}");
                Log::error("Account sync error for {$integration->provider}: {$e->getMessage()}");
            }
        }

        $this->info('Account sync completed.');
        return self::SUCCESS;
    }
} 