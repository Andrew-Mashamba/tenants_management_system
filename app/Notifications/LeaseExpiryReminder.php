<?php

namespace App\Notifications;

use App\Models\Lease;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LeaseExpiryReminder extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected Lease $lease,
        protected int $daysUntilExpiry
    ) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        $message = (new MailMessage)
            ->subject('Lease Expiry Reminder')
            ->greeting("Hello {$notifiable->name},")
            ->line("The lease for {$this->lease->unit->property->name} - Unit {$this->lease->unit->unit_number} is expiring in {$this->daysUntilExpiry} days.")
            ->line("Lease Details:")
            ->line("- Property: {$this->lease->unit->property->name}")
            ->line("- Unit: {$this->lease->unit->unit_number}")
            ->line("- Tenant: {$this->lease->tenant->name}")
            ->line("- End Date: {$this->lease->end_date->format('M d, Y')}");

        if ($this->daysUntilExpiry <= 30) {
            $message->action('Initiate Renewal', route('leases.workflows.create', $this->lease));
        }

        return $message;
    }

    public function toArray($notifiable): array
    {
        return [
            'lease_id' => $this->lease->id,
            'days_until_expiry' => $this->daysUntilExpiry,
            'property_name' => $this->lease->unit->property->name,
            'unit_number' => $this->lease->unit->unit_number,
            'end_date' => $this->lease->end_date->format('Y-m-d'),
        ];
    }
} 