<?php

namespace App\Livewire\Communication;

use App\Models\Announcement;
use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;

class AnnouncementForm extends Component
{
    use WithFileUploads;

    public $announcement;
    public $title;
    public $content;
    public $type;
    public $priority;
    public $start_date;
    public $end_date;
    public $is_published = false;
    public $target_audience = [];
    public $specific_recipients = [];
    public $available_recipients = [];

    protected $rules = [
        'title' => 'required|string|max:255',
        'content' => 'required|string',
        'type' => 'required|in:general,maintenance,emergency,event',
        'priority' => 'required|in:low,medium,high',
        'start_date' => 'nullable|date',
        'end_date' => 'nullable|date|after_or_equal:start_date',
        'is_published' => 'boolean',
        'target_audience' => 'array',
        'target_audience.*' => 'in:all,tenants,owners,staff',
        'specific_recipients' => 'array',
        'specific_recipients.*' => 'exists:users,id'
    ];

    public function mount($announcement = null)
    {
        if ($announcement) {
            $this->announcement = $announcement;
            $this->title = $announcement->title;
            $this->content = $announcement->content;
            $this->type = $announcement->type;
            $this->priority = $announcement->priority;
            $this->start_date = $announcement->start_date?->format('Y-m-d\TH:i');
            $this->end_date = $announcement->end_date?->format('Y-m-d\TH:i');
            $this->is_published = $announcement->is_published;
            $this->target_audience = $announcement->target_audience ?? [];
            $this->specific_recipients = $announcement->specific_recipients ?? [];
        }

        $this->available_recipients = User::select('id', 'name', 'email')
            ->orderBy('name')
            ->get();
    }

    public function save()
    {
        $this->validate();

        $data = [
            'title' => $this->title,
            'content' => $this->content,
            'type' => $this->type,
            'priority' => $this->priority,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'is_published' => $this->is_published,
            'target_audience' => $this->target_audience,
            'specific_recipients' => $this->specific_recipients,
            'created_by' => Auth::id()
        ];

        if ($this->announcement) {
            $this->announcement->update($data);
            $message = 'Announcement updated successfully.';
        } else {
            $this->announcement = Announcement::create($data);
            $message = 'Announcement created successfully.';
        }

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => $message
        ]);

        return redirect()->route('announcements.show', $this->announcement);
    }

    public function render()
    {
        return view('livewire.communication.announcement-form');
    }
} 