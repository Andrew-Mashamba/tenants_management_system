<?php

namespace App\Events;

use App\Models\SmartLock;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LockStatusUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $lock;
    public $result;

    public function __construct(SmartLock $lock, array $result)
    {
        $this->lock = $lock;
        $this->result = $result;
    }

    public function broadcastOn()
    {
        return new PrivateChannel("property.{$this->lock->property_id}.locks");
    }

    public function broadcastWith()
    {
        return [
            'lock_id' => $this->lock->id,
            'status' => $this->result['lock_status'] ?? null,
            'action' => $this->result['action'],
            'timestamp' => $this->result['timestamp'],
        ];
    }
} 