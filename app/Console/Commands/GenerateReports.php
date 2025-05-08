<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Lease;
use App\Models\Payment;
use Carbon\Carbon;

class GenerateReports extends Command
{
    protected $signature = 'reports:generate';
    protected $description = 'Generate daily reports for leases and payments';

    public function handle()
    {
        $this->info('Generating daily reports...');
        
        // Generate lease reports
        $activeLeases = Lease::where('status', 'active')->count();
        $expiringLeases = Lease::where('end_date', '<=', Carbon::now()->addDays(30))->count();
        
        // Generate payment reports
        $totalPayments = Payment::whereDate('created_at', Carbon::today())->sum('amount');
        $pendingPayments = Payment::where('status', 'pending')->count();
        
        $this->info("Active Leases: {$activeLeases}");
        $this->info("Expiring Leases (30 days): {$expiringLeases}");
        $this->info("Today's Payments: {$totalPayments}");
        $this->info("Pending Payments: {$pendingPayments}");
        
        $this->info('Report generation completed');
    }
} 