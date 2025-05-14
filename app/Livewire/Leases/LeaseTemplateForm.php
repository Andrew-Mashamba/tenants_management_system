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

    public function resetForm()
    {
        $this->template = null;
        $this->name = '';
        $this->description = '';
        $this->content = '';
        $this->variables = [];
        $this->is_active = true;
        $this->is_default = false;
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

        // return redirect()->route('leases.templates.index');
        session()->flash('success', $message);
        $this->resetForm();
    }

    public function selectTemplate($id)
    {
        $this->template = LeaseTemplate::find($id);
        $this->name = $this->template->name;
        $this->description = $this->template->description;
        $this->content = $this->template->content;
        $this->variables = $this->template->variables;
        $this->is_active = $this->template->is_active;
        $this->is_default = $this->template->is_default;
    }
    

    public function render()
    {
        $templates = LeaseTemplate::all();
        return view('livewire.leases.lease-template-form', compact('templates'));
    }
} 