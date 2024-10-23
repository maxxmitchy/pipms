<?php

namespace App\Livewire\Medications;

use App\Models\Organization;
use App\Services\ExportService;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class Expiring extends Component
{
    use WithPagination;

    public $search = '';

    public $sortField = 'expiration_date';

    public $sortDirection = 'asc';

    public $showDeleteModal = false;

    public $medicationToDelete = null;

    public $organization;

    protected $queryString = ['search', 'sortField', 'sortDirection'];

    public function mount(Organization $organization)
    {
        $this->organization = $organization;
    }

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

    public function confirmDelete($id)
    {
        $this->medicationToDelete = $id;
        $this->showDeleteModal = true;
    }

    public function deleteMedication()
    {
        $medication = $this->organization->inventories()->findOrFail($this->medicationToDelete);
        $medication->delete();
        $this->showDeleteModal = false;
        $this->medicationToDelete = null;
        session()->flash('message', 'Medication deleted successfully.');
    }

    public function editMedication($id)
    {
        return redirect()->route('medications.edit', $id);
    }

    public function export($format, ExportService $exportService)
    {
        session()->flash('message', "Export to {$format} is not implemented yet.");

        // $path = $exportService->exportLowStock($this->organizationId, $format);
        // return response()->download($path)->deleteFileAfterSend();
    }

    #[Layout('layouts.app')]
    public function render()
    {
        $threeMonthsFromNow = now()->addMonths(3);

        $expiring = $this->organization->inventories()
            ->where('expiration_date', '<=', $threeMonthsFromNow)
            ->where('expiration_date', '>', now())
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%'.$this->search.'%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.medications.expiring', [
            'expiring' => $expiring,
        ]);
    }
}
