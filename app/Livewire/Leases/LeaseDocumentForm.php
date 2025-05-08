<?php

namespace App\Livewire\Leases;

use App\Models\Lease;
use App\Models\LeaseDocument;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class LeaseDocumentForm extends Component
{
    use WithFileUploads;

    public $lease;
    public $document;
    public $name;
    public $type;
    public $file;
    public $description;
    public $expiry_date;
    public $requires_renewal = false;
    public $renewal_reminder_days = 30;

    protected $rules = [
        'name' => 'required|string|max:255',
        'type' => 'required|string|max:50',
        'file' => 'required|file|max:10240', // 10MB max
        'description' => 'nullable|string|max:1000',
        'expiry_date' => 'nullable|date|after:today',
        'requires_renewal' => 'boolean',
        'renewal_reminder_days' => 'required_if:requires_renewal,true|integer|min:1|max:365',
    ];

    public function mount($lease, $document = null)
    {
        $this->lease = $lease;
        $this->document = $document;

        if ($document) {
            $this->name = $document->name;
            $this->type = $document->type;
            $this->description = $document->description;
            $this->expiry_date = $document->expiry_date?->format('Y-m-d');
            $this->requires_renewal = $document->requires_renewal;
            $this->renewal_reminder_days = $document->renewal_reminder_days;
        }
    }

    public function save()
    {
        $this->validate();

        $file = $this->file->store('lease-documents');

        $data = [
            'lease_id' => $this->lease->id,
            'name' => $this->name,
            'type' => $this->type,
            'file_path' => $file,
            'mime_type' => $this->file->getMimeType(),
            'size' => $this->file->getSize(),
            'description' => $this->description,
            'expiry_date' => $this->expiry_date,
            'requires_renewal' => $this->requires_renewal,
            'renewal_reminder_days' => $this->requires_renewal ? $this->renewal_reminder_days : null,
        ];

        if ($this->document) {
            // Delete old file
            Storage::delete($this->document->file_path);
            $this->document->update($data);
            $message = 'Document updated successfully.';
        } else {
            $document = LeaseDocument::create($data);
            if ($document->requires_renewal) {
                $document->createRenewalReminder();
            }
            $message = 'Document uploaded successfully.';
        }

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => $message
        ]);

        return redirect()->route('leases.show', $this->lease);
    }

    public function render()
    {
        return view('livewire.leases.lease-document-form');
    }
} 