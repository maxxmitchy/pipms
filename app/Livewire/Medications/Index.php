<?php

namespace App\Livewire\Medications;

use App\Models\Medication;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';

    public $medicationToDelete = null;

    public $view = 'card';

    public $sortField = 'name';

    public $sortDirection = 'asc';

    protected $queryString = ['search', 'view', 'sortField', 'sortDirection'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function setView($view)
    {
        $this->view = $view;
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

    public function confirmDelete($medicationId)
    {
        $this->medicationToDelete = $medicationId;
    }

    public function cancelDelete()
    {
        $this->medicationToDelete = null;
    }

    public function delete()
    {
        $medication = Medication::findOrFail($this->medicationToDelete);
        $medication->delete();
        session()->flash('message', 'Medication deleted successfully.');
        $this->medicationToDelete = null;
    }

    #[Layout('layouts.app')]
    public function render()
    {
        $medications = Medication::with('brand')
            ->where(function ($query) {
                $query->where('name', 'like', '%'.$this->search.'%')
                    ->orWhere('description', 'like', '%'.$this->search.'%')
                    ->orWhere('dosage', 'like', '%'.$this->search.'%')
                    ->orWhere('manufacturer', 'like', '%'.$this->search.'%')
                    ->orWhereHas('brand', function ($query) {
                        $query->where('name', 'like', '%'.$this->search.'%');
                    });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(9);

        return view('livewire.medications.index', [
            'medications' => $medications,
        ]);
    }
}
