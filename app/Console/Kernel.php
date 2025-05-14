<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        Commands\ProcessLateFees::class,
        Commands\ProcessScheduledMessages::class,
        Commands\GenerateReports::class,
        Commands\SendDocumentExpiryAlerts::class,
    ];

    protected function schedule(Schedule $schedule): void
    {
        // Process late fees daily at midnight
        $schedule->command('payments:process-late-fees')
            ->daily()
            ->appendOutputTo(storage_path('logs/late-fees.log'));

        // Process scheduled messages every minute
        $schedule->command('messages:process-scheduled')->everyMinute();

        // Generate reports daily at 1 AM
        $schedule->command('reports:generate')
            ->dailyAt('01:00')
            ->appendOutputTo(storage_path('logs/reports.log'));

        // Schedule document expiry alerts to run daily at 9 AM
        $schedule->command('documents:send-expiry-alerts')
            ->dailyAt('09:00')
            ->appendOutputTo(storage_path('logs/document-alerts.log'));

        // Send document renewal reminders daily
        $schedule->command('leases:send-document-reminders')
            ->daily()
            ->at('09:00')
            ->withoutOverlapping();

        // Update tenants status daily
        $schedule->job(new \App\Jobs\TenantsStatus)->daily();
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
} 