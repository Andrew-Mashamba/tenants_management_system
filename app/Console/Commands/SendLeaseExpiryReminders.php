<?php

namespace App\Console\Commands;

use App\Models\Lease;
use App\Notifications\LeaseExpiryReminder;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class SendLeaseExpiryReminders extends Command
{
    protected $signature = 'leases:send-expiry-reminders {--days=30}';
    protected $description = 'Send reminders for leases that are expiring soon';

    public function handle()
    {
        $days = $this->option('days');
        $expiryDate = Carbon::now()->addDays($days);

        $leases = Lease::where('end_date', '<=', $expiryDate)
            ->where('end_date', '>', Carbon::now())
            ->whereDoesntHave('workflows', function ($query) {
                $query->whereIn('status', ['initiated', 'pending_approval']);
            })
            ->get();

        foreach ($leases as $lease) {
            $daysUntilExpiry = Carbon::now()->diffInDays($lease->end_date);

            $lease->tenant->notify(new LeaseExpiryReminder($lease, $daysUntilExpiry));
            $lease->landlord->notify(new LeaseExpiryReminder($lease, $daysUntilExpiry));

            $this->info("Sent reminder for lease #{$lease->id} expiring in {$daysUntilExpiry} days");
        }

        $this->info("Sent {$leases->count()} lease expiry reminders");
    }
} 