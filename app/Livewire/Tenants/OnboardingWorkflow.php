<?php

namespace App\Livewire\Tenants;

use App\Models\TenantOnboardingWorkflow;
use App\Models\Property;
use App\Models\Unit;
use Livewire\Component;

class OnboardingWorkflow extends Component
{
    public $tenant;
    public $workflow;
    public $property_id;
    public $unit_id;
    public $properties;
    public $units = [];
    public $currentStep = 1;
    public $totalSteps = 7;

    protected $rules = [
        'property_id' => 'required|exists:properties,id',
        'unit_id' => 'required|exists:units,id',
    ];

    public function mount($tenant)
    {
        $this->tenant = $tenant;
        $this->workflow = $tenant->onboardingWorkflow;
        $this->properties = Property::where('status', 'active')->get();

        if ($this->workflow) {
            $this->property_id = $this->workflow->property_id;
            $this->unit_id = $this->workflow->unit_id;
            $this->loadUnits();
            $this->setCurrentStep();
        }
    }

    public function updatedPropertyId($value)
    {
        $this->loadUnits();
        $this->unit_id = null;
    }

    public function loadUnits()
    {
        if ($this->property_id) {
            $this->units = Unit::where('property_id', $this->property_id)
                ->where('status', 'available')
                ->get();
        }
    }

    public function setCurrentStep()
    {
        if (!$this->workflow) {
            $this->currentStep = 1;
            return;
        }

        switch ($this->workflow->status) {
            case 'application_submitted':
                $this->currentStep = 2;
                break;
            case 'documents_uploaded':
                $this->currentStep = 3;
                break;
            case 'kyc_verified':
                $this->currentStep = 4;
                break;
            case 'background_check_completed':
                $this->currentStep = 5;
                break;
            case 'lease_signed':
                $this->currentStep = 6;
                break;
            case 'completed':
                $this->currentStep = 7;
                break;
            default:
                $this->currentStep = 1;
        }
    }

    public function startOnboarding()
    {
        $this->validate();

        $workflow = TenantOnboardingWorkflow::create([
            'tenant_id' => $this->tenant->id,
            'property_id' => $this->property_id,
            'unit_id' => $this->unit_id,
            'status' => 'application_submitted',
            'pending_steps' => [
                'documents_upload',
                'kyc_verification',
                'background_check',
                'lease_signing',
                'payment',
            ],
        ]);

        $this->workflow = $workflow;
        $this->currentStep = 2;
        session()->flash('message', 'Onboarding process started successfully.');
    }

    public function completeStep($step)
    {
        if (!$this->workflow) {
            return;
        }

        $this->workflow->completeStep($step);
        $this->updateWorkflowStatus();
        $this->setCurrentStep();
    }

    protected function updateWorkflowStatus()
    {
        if (!$this->workflow) {
            return;
        }

        $completedSteps = $this->workflow->completed_steps ?? [];
        $status = 'application_submitted';

        if (in_array('documents_upload', $completedSteps)) {
            $status = 'documents_uploaded';
        }
        if (in_array('kyc_verification', $completedSteps)) {
            $status = 'kyc_verified';
        }
        if (in_array('background_check', $completedSteps)) {
            $status = 'background_check_completed';
        }
        if (in_array('lease_signing', $completedSteps)) {
            $status = 'lease_signed';
        }
        if (in_array('payment', $completedSteps)) {
            $status = 'completed';
            $this->workflow->markAsCompleted();
        }

        $this->workflow->update(['status' => $status]);
    }

    public function render()
    {
        return view('livewire.tenants.onboarding-workflow', [
            'steps' => [
                1 => 'Select Property & Unit',
                2 => 'Submit Application',
                3 => 'Upload Documents',
                4 => 'KYC Verification',
                5 => 'Background Check',
                6 => 'Sign Lease',
                7 => 'Complete Payment',
            ],
        ]);
    }
}
