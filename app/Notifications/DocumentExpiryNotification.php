<?php

namespace App\Notifications;

use App\Models\Document;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DocumentExpiryNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Document $document)
    {
    }

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Document Expiry Alert')
            ->greeting('Hello ' . $notifiable->name)
            ->line('This is a reminder that the following document is about to expire:')
            ->line('Document: ' . $this->document->title)
            ->line('Expiry Date: ' . $this->document->expiry_date->format('Y-m-d'))
            ->action('View Document', route('documents.show', $this->document))
            ->line('Please take necessary action to update or renew this document.');
    }

    public function toArray($notifiable): array
    {
        return [
            'document_id' => $this->document->id,
            'title' => $this->document->title,
            'expiry_date' => $this->document->expiry_date->format('Y-m-d'),
            'type' => 'document_expiry',
        ];
    }
} 