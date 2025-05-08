<?php

namespace App\Livewire\Leases;

use App\Models\LeaseTemplate;
use Livewire\Component;
use Livewire\WithPagination;

class LeaseTemplateList extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'name';
    public $sortDirection = 'asc';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function delete($id)
    {
        $template = LeaseTemplate::findOrFail($id);
        
        if ($template->is_default) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Cannot delete the default template.'
            ]);
            return;
        }

        if ($template->leases()->exists()) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Cannot delete template that is being used by leases.'
            ]);
            return;
        }

        $template->delete();

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Template deleted successfully.'
        ]);
    }

    public function render()
    {
        $templates = LeaseTemplate::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.leases.lease-template-list', [
            'templates' => $templates
        ]);
    }
} 