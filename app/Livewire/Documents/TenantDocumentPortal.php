<?php

namespace App\Livewire\Documents;

use App\Models\Document;
use App\Models\DocumentCategory;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class TenantDocumentPortal extends Component
{
    use WithPagination;

    public $selectedCategory = '';
    public $search = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    protected $queryString = [
        'selectedCategory' => ['except' => ''],
        'search' => ['except' => ''],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc']
    ];

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function getCategoriesProperty()
    {
        return DocumentCategory::orderBy('name')->get();
    }

    public function getDocumentsProperty()
    {
        return Document::query()
            ->whereHas('tenantAccess', function ($query) {
                $query->where('tenant_id', Auth::user()->tenant->id)
                    ->where('can_view', true);
            })
            ->when($this->selectedCategory, function ($query) {
                $query->whereHas('categories', function ($q) {
                    $q->where('document_categories.id', $this->selectedCategory);
                });
            })
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('title', 'like', '%' . $this->search . '%')
                        ->orWhere('description', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);
    }

    public function downloadDocument(Document $document)
    {
        if (!$document->tenantAccess()->where('tenant_id', Auth::user()->tenant->id)->where('can_download', true)->exists()) {
            $this->addError('download', 'You do not have permission to download this document.');
            return;
        }

        return response()->download(storage_path('app/' . $document->file_path));
    }

    public function render()
    {
        return view('livewire.documents.tenant-document-portal', [
            'categories' => $this->categories,
            'documents' => $this->documents,
        ]);
    }
} 