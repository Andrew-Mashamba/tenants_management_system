<?php

namespace App\Notifications;

use App\Models\MaintenanceRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MaintenanceStatusUpdateNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private MaintenanceRequest $request,
        private string $status,
        private ?string $notes = null
    ) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Maintenance Request Status Update: {$this->request->title}")
            ->line("The status of your maintenance request has been updated to: {$this->status}")
            ->lineIf($this->notes, "Notes: {$this->notes}")
            ->action('View Request', route('maintenance-requests.show', $this->request));
    }

    public function toArray($notifiable)
    {
        return [
            'maintenance_request_id' => $this->request->id,
            'title' => $this->request->title,
            'status' => $this->status,
            'notes' => $this->notes,
            'property_name' => $this->request->property->name,
            'unit_number' => $this->request->unit?->unit_number,
            'priority' => $this->request->priority,
            'scheduled_date' => $this->request->scheduled_date?->format('Y-m-d H:i'),
            'completed_date' => $this->request->completed_date?->format('Y-m-d H:i')
        ];
    }
} 