<?php

namespace App\Livewire\Leases;

use App\Models\LeaseTemplate;
use Livewire\Component;
use Livewire\WithFileUploads;

class LeaseTemplateForm extends Component
{
    use WithFileUploads;

    public $template;
    public $name;
    public $description;
    public $content;
    public $variables = [];
    public $is_active = true;
    public $is_default = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'content' => 'required|string',
        'variables' => 'nullable|array',
        'is_active' => 'boolean',
        'is_default' => 'boolean',
    ];

    public function mount($template = null)
    {
        if ($template) {
            $this->template = $template;
            $this->name = $template->name;
            $this->description = $template->description;
            $this->content = $template->content;
            $this->variables = $template->variables ?? [];
            $this->is_active = $template->is_active;
            $this->is_default = $template->is_default;
        }
    }

    public function save()
    {
        $this->validate();

        if ($this->is_default) {
            LeaseTemplate::where('is_default', true)->update(['is_default' => false]);
        }

        $data = [
            'name' => $this->name,
            'description' => $this->description,
            'content' => $this->content,
            'variables' => $this->variables,
            'is_active' => $this->is_active,
            'is_default' => $this->is_default,
        ];

        if ($this->template) {
            $this->template->update($data);
            $message = 'Template updated successfully.';
        } else {
            LeaseTemplate::create($data);
            $message = 'Template created successfully.';
        }

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => $message
        ]);

        return redirect()->route('leases.templates.index');
    }

    public function render()
    {
        return view('livewire.leases.lease-template-form');
    }
} 