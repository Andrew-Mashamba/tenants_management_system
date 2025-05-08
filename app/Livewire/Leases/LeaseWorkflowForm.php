<?php

namespace App\Livewire\Leases;

use App\Models\Lease;
use App\Models\LeaseWorkflow;
use Livewire\Component;
use Livewire\WithFileUploads;

class LeaseWorkflowForm extends Component
{
    use WithFileUploads;

    public $lease;
    public $workflow;
    public $type = 'renewal';
    public $effective_date;
    public $new_expiry_date;
    public $reason;
    public $steps = [];

    protected $rules = [
        'type' => 'required|in:renewal,termination',
        'effective_date' => 'required|date|after_or_equal:today',
        'new_expiry_date' => 'required_if:type,renewal|date|after:effective_date',
        'reason' => 'required|string|max:1000',
    ];

    public function mount($lease, $workflow = null)
    {
        $this->lease = $lease;
        $this->workflow = $workflow;

        if ($workflow) {
            $this->type = $workflow->type;
            $this->effective_date = $workflow->effective_date->format('Y-m-d');
            $this->new_expiry_date = $workflow->new_expiry_date?->format('Y-m-d');
            $this->reason = $workflow->reason;
            $this->steps = $workflow->steps ?? [];
        }
    }

    public function save()
    {
        $this->validate();

        $data = [
            'lease_id' => $this->lease->id,
            'type' => $this->type,
            'effective_date' => $this->effective_date,
            'new_expiry_date' => $this->type === 'renewal' ? $this->new_expiry_date : null,
            'reason' => $this->reason,
            'status' => 'pending',
            'steps' => $this->getWorkflowSteps(),
        ];

        if ($this->workflow) {
            $this->workflow->update($data);
            $message = 'Workflow updated successfully.';
        } else {
            LeaseWorkflow::create($data);
            $message = 'Workflow initiated successfully.';
        }

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => $message
        ]);

        return redirect()->route('leases.show', $this->lease);
    }

    protected function getWorkflowSteps()
    {
        $steps = [
            'renewal' => [
                ['name' => 'Initiation', 'status' => 'pending'],
                ['name' => 'Tenant Review', 'status' => 'pending'],
                ['name' => 'Approval', 'status' => 'pending'],
                ['name' => 'Document Generation', 'status' => 'pending'],
                ['name' => 'Signing', 'status' => 'pending'],
                ['name' => 'Completion', 'status' => 'pending'],
            ],
            'termination' => [
                ['name' => 'Initiation', 'status' => 'pending'],
                ['name' => 'Tenant Notification', 'status' => 'pending'],
                ['name' => 'Approval', 'status' => 'pending'],
                ['name' => 'Document Generation', 'status' => 'pending'],
                ['name' => 'Signing', 'status' => 'pending'],
                ['name' => 'Completion', 'status' => 'pending'],
            ],
        ];

        return $steps[$this->type];
    }

    public function render()
    {
        return view('livewire.leases.lease-workflow-form');
    }
} 