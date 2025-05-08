<?php

namespace App\Livewire\Tenants;

use App\Models\TenantKycVerification;
use Livewire\Component;
use Livewire\WithFileUploads;

class KycVerificationForm extends Component
{
    use WithFileUploads;

    public $tenant;
    public $kycVerification;
    public $id_type;
    public $id_number;
    public $id_expiry_date;
    public $id_document;
    public $proof_of_income;
    public $employment_verification;
    public $verification_notes;

    protected $rules = [
        'id_type' => 'required|string',
        'id_number' => 'required|string',
        'id_expiry_date' => 'required|date|after:today',
        'id_document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
        'proof_of_income' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
        'employment_verification' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
        'verification_notes' => 'nullable|string',
    ];

    public function mount($tenant)
    {
        $this->tenant = $tenant;
        $this->kycVerification = $tenant->kycVerification;

        if ($this->kycVerification) {
            $this->id_type = $this->kycVerification->id_type;
            $this->id_number = $this->kycVerification->id_number;
            $this->id_expiry_date = $this->kycVerification->id_expiry_date?->format('Y-m-d');
            $this->verification_notes = $this->kycVerification->verification_notes;
        }
    }

    public function save()
    {
        $this->validate();

        $data = [
            'tenant_id' => $this->tenant->id,
            'id_type' => $this->id_type,
            'id_number' => $this->id_number,
            'id_expiry_date' => $this->id_expiry_date,
            'verification_notes' => $this->verification_notes,
            'status' => 'pending',
        ];

        if ($this->id_document) {
            $data['id_document_path'] = $this->id_document->store('kyc/id_documents', 'public');
        }

        if ($this->proof_of_income) {
            $data['proof_of_income_path'] = $this->proof_of_income->store('kyc/income_proofs', 'public');
        }

        if ($this->employment_verification) {
            $data['employment_verification_path'] = $this->employment_verification->store('kyc/employment_verifications', 'public');
        }

        if ($this->kycVerification) {
            $this->kycVerification->update($data);
            session()->flash('message', 'KYC verification updated successfully.');
        } else {
            TenantKycVerification::create($data);
            session()->flash('message', 'KYC verification submitted successfully.');
        }

        return redirect()->route('tenant.profile');
    }

    public function render()
    {
        return view('livewire.tenants.kyc-verification-form', [
            'idTypes' => [
                'passport' => 'Passport',
                'drivers_license' => 'Driver\'s License',
                'national_id' => 'National ID',
                'other' => 'Other',
            ],
        ]);
    }
}
