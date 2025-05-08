<?php

namespace App\Livewire\Tenants;

use App\Models\Tenant;
use App\Models\Property;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class TenantForm extends Component
{
    use WithFileUploads;

    public $tenant;
    public $properties;
    public $name;
    public $email;
    public $phone;
    public $property_id;
    public $address;
    public $city;
    public $state;
    public $postal_code;
    public $country;
    public $emergency_contact;
    public $emergency_phone;
    public $notes;
    public $documents = [];
    public $existingDocuments = [];
    public $documentsToDelete = [];
    public $showDeleteModal = false;
    public $documentToDelete = null;
    public $uploadedFiles = [];
    public $uploadProgress = [];

    protected $listeners = ['fileUploaded' => 'handleFileUpload'];

    public function mount($tenant = null)
    {
        $this->properties = Property::all();
        
        $tenant = Tenant::find($tenant);
        if ($tenant) {
            $this->tenant = $tenant;
            $this->name = $tenant->name;
            $this->email = $tenant->email;
            $this->phone = $tenant->phone;
            $this->property_id = $tenant->property_id;
            $this->address = $tenant->address;
            $this->city = $tenant->city;
            $this->state = $tenant->state;
            $this->postal_code = $tenant->postal_code;
            $this->country = $tenant->country;
            $this->emergency_contact = $tenant->emergency_contact;
            $this->emergency_phone = $tenant->emergency_phone;
            $this->notes = $tenant->notes;
            $this->existingDocuments = $tenant->documents()->get();            
        }
    }

    public function updatedDocuments()
    {
        $this->validate([
            'documents.*' => 'file|max:10240', // 10MB max
        ]);

        foreach ($this->documents as $document) {
            $this->uploadedFiles[] = [
                'name' => $document->getClientOriginalName(),
                'size' => $document->getSize(),
                'type' => $document->getClientMimeType(),
                'path' => $document->getRealPath(),
                'preview' => $this->getFilePreview($document),
            ];
        }
    }

    public function removeUploadedFile($index)
    {
        if (isset($this->uploadedFiles[$index])) {
            unset($this->uploadedFiles[$index]);
            $this->uploadedFiles = array_values($this->uploadedFiles);
        }
    }

    protected function getFilePreview($file)
    {
        if (str_starts_with($file->getClientMimeType(), 'image/')) {
            return $file->temporaryUrl();
        }
        return null;
    }

    public function confirmDeleteDocument($documentId)
    {
        $this->documentToDelete = $documentId;
        $this->showDeleteModal = true;
    }

    public function cancelDelete()
    {
        $this->documentToDelete = null;
        $this->showDeleteModal = false;
    }

    public function removeDocument()
    {
        if ($this->documentToDelete) {
            $this->documentsToDelete[] = $this->documentToDelete;
            $this->existingDocuments = $this->existingDocuments->filter(function($doc) {
                return $doc->id !== $this->documentToDelete;
            });
            $this->documentToDelete = null;
            $this->showDeleteModal = false;
        }
    }

    public function save()
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:tenants,email' . ($this->tenant ? ',' . $this->tenant->id : ''),
            'phone' => 'required|string|max:20',
            'property_id' => 'required|exists:properties,id',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:255',
            'emergency_contact' => 'nullable|string|max:255',
            'emergency_phone' => 'nullable|string|max:20',
            'notes' => 'nullable|string',
            'documents.*' => 'nullable|file|max:10240', // 10MB max
        ]);

        if ($this->tenant) {
            $this->tenant->update($validated);
        } else {
            $this->tenant = Tenant::create($validated);
        }

        // Handle document uploads
        if ($this->documents) {
            foreach ($this->documents as $document) {
                $path = $document->store('tenant-documents/' . $this->tenant->id, 'public');
                $this->tenant->documents()->create([
                    'file_path' => $path,
                    'file_name' => $document->getClientOriginalName(),
                    'file_type' => $document->getClientMimeType(),
                    'file_size' => $document->getSize(),
                    'uploaded_by' => auth()->user()->id,
                ]);
            }
        }

        // Handle document deletions
        if (!empty($this->documentsToDelete)) {
            $documentsToDelete = $this->tenant->documents()->whereIn('id', $this->documentsToDelete)->get();
            foreach ($documentsToDelete as $document) {
                if (Storage::disk('public')->exists($document->file_path)) {
                    Storage::disk('public')->delete($document->file_path);
                }
                $document->delete();
            }
        }

        session()->flash('message', $this->tenant->wasRecentlyCreated ? 'Tenant created successfully.' : 'Tenant updated successfully.');
        
        return redirect()->route('tenants.index');
    }

    public function getDocumentUrl($path)
    {
        return Storage::disk('public')->url($path);
    }

    public function render()
    {
        return view('livewire.tenants.tenant-form');
    }
} 