<?php

namespace App\Services;

use App\Models\BulkMessage;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\BulkEmailMessage;
use App\Notifications\BulkSmsMessage;

class BulkMessagingService
{
    public function send(BulkMessage $message): void
    {
        try {
            $message->markAsSending();
            $recipients = $this->getRecipients($message->recipients);

            foreach ($recipients as $recipient) {
                try {
                    if ($message->channel === 'email' || $message->channel === 'both') {
                        $this->sendEmail($recipient, $message);
                    }

                    if ($message->channel === 'sms' || $message->channel === 'both') {
                        $this->sendSms($recipient, $message);
                    }

                    $message->updateDeliveryStatus($recipient->id, 'delivered');
                } catch (\Exception $e) {
                    Log::error("Failed to send message to user {$recipient->id}: " . $e->getMessage());
                    $message->updateDeliveryStatus($recipient->id, 'failed', $e->getMessage());
                }
            }

            $message->markAsSent();
        } catch (\Exception $e) {
            Log::error("Bulk message sending failed: " . $e->getMessage());
            $message->markAsFailed();
        }
    }

    protected function getRecipients(array $recipientData): \Illuminate\Database\Eloquent\Collection
    {
        $userIds = [];
        $roles = [];

        foreach ($recipientData as $item) {
            if (is_numeric($item)) {
                $userIds[] = $item;
            } else {
                $roles[] = $item;
            }
        }

        return User::where(function ($query) use ($userIds, $roles) {
            if (!empty($userIds)) {
                $query->whereIn('id', $userIds);
            }
            if (!empty($roles)) {
                $query->orWhereIn('role', $roles);
            }
        })->get();
    }

    protected function sendEmail(User $recipient, BulkMessage $message): void
    {
        if (!$recipient->notificationPreference?->shouldReceiveEmail()) {
            return;
        }

        Mail::to($recipient->email)
            ->send(new BulkEmailMessage($message));
    }

    protected function sendSms(User $recipient, BulkMessage $message): void
    {
        if (!$recipient->notificationPreference?->shouldReceiveSms() || !$recipient->phone) {
            return;
        }

        $recipient->notify(new BulkSmsMessage($message));
    }

    public function schedule(BulkMessage $message): void
    {
        if (!$message->scheduled_at) {
            $message->update(['scheduled_at' => now()]);
        }
    }
} 