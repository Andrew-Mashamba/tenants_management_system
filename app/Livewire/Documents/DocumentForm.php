<?php

namespace App\Livewire\Documents;

use App\Models\Document;
use Livewire\Component;
use Livewire\WithFileUploads;

class DocumentForm extends Component
{
    use WithFileUploads;

    public ?Document $document = null;
    public $name = '';
    public $description = '';
    public $file;
    public $category_id;

    public function mount(?Document $document = null)
    {
        $this->document = $document;
        if ($document) {
            $this->name = $document->name;
            $this->description = $document->description;
            $this->category_id = $document->category_id;
        }
    }

    public function save()
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'file' => $this->document ? 'nullable|file|max:10240' : 'required|file|max:10240',
            'category_id' => 'required|exists:document_categories,id',
        ]);

        if ($this->file) {
            $validated['file_path'] = $this->file->store('documents');
        }

        if ($this->document) {
            $this->document->update($validated);
        } else {
            Document::create($validated);
        }

        return redirect()->route('documents.index');
    }

    public function render()
    {
        return view('livewire.documents.document-form');
    }
} 