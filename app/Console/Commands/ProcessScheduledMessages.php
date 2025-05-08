<?php

namespace App\Console\Commands;

use App\Models\BulkMessage;
use App\Services\BulkMessagingService;
use Illuminate\Console\Command;

class ProcessScheduledMessages extends Command
{
    protected $signature = 'messages:process-scheduled';
    protected $description = 'Process scheduled bulk messages';

    public function __construct(private BulkMessagingService $messagingService)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $messages = BulkMessage::readyToSend()->get();

        if ($messages->isEmpty()) {
            $this->info('No scheduled messages to process.');
            return self::SUCCESS;
        }

        $this->info("Processing {$messages->count()} scheduled messages...");

        foreach ($messages as $message) {
            $this->info("Processing message ID: {$message->id}");
            $this->messagingService->send($message);
        }

        $this->info('All scheduled messages processed.');
        return self::SUCCESS;
    }
} 