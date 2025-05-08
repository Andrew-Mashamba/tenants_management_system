<?php

namespace App\Livewire\Communication;

use App\Models\Message;
use Livewire\Component;

class MessageForm extends Component
{
    public $message = null;
    public $subject = '';
    public $content = '';
    public $recipients = [];

    public function mount(Message $message = null)
    {
        if ($message) {
            $this->message = $message;
            $this->subject = $message->subject;
            $this->content = $message->content;
            $this->recipients = $message->recipients;
        }
    }

    public function save()
    {
        $validated = $this->validate([
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'recipients' => 'required|array',
        ]);

        if ($this->message) {
            $this->message->update($validated);
        } else {
            Message::create($validated);
        }

        $this->dispatch('message-saved');
    }

    public function render()
    {
        return view('livewire.communication.message-form');
    }
} 