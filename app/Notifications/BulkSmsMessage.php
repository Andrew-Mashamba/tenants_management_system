<?php

namespace App\Notifications;

use App\Models\BulkMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class BulkSmsMessage extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public BulkMessage $message)
    {
    }

    public function via($notifiable): array
    {
        return ['sms'];
    }

    public function toSms($notifiable): string
    {
        return $this->message->content;
    }
} 