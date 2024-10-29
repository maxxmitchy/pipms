<?php

namespace App\Livewire\Medications;

use App\Models\Organization;
use App\Models\Inventory;
use App\Services\ExportService;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class Expiring extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'expiration_date';
    public $sortDirection = 'asc';
    public $showDeleteModal = false;
    public $medicationToDelete = null;
    public $organization;
    public $isSuperAdmin;

    protected $queryString = ['search', 'sortField', 'sortDirection'];

    public function mount()
    {
        $this->isSuperAdmin = Auth::user()->hasRole('super-admin');
        $this->organization = $this->isSuperAdmin ? null : Auth::user()->organization;
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
        $medication = Inventory::findOrFail($this->medicationToDelete);
        if ($this->isSuperAdmin || $medication->organization_id === Auth::user()->organization_id) {
            $medication->delete();
            session()->flash('message', 'Medication deleted successfully.');
        } else {
            session()->flash('error', 'You do not have permission to delete this medication.');
        }
        $this->showDeleteModal = false;
        $this->medicationToDelete = null;
    }

    public function editMedication($id)
    {
        return redirect()->route('medications.edit', $id);
    }

    public function export($format, ExportService $exportService)
    {
        $organizationId = $this->isSuperAdmin ? null : $this->organization->id;
        $path = $exportService->exportExpiringMedications($organizationId, $format);
        return response()->download($path)->deleteFileAfterSend();
    }

    #[Layout('layouts.app')]
    public function render()
    {
        $threeMonthsFromNow = now()->addMonths(3);

        $query = $this->isSuperAdmin ? Inventory::query() : $this->organization->inventories();

        $expiring = $query
            ->where('expiration_date', '<=', $threeMonthsFromNow)
            ->where('expiration_date', '>', now())
            ->when($this->search, function ($query) {
                $query->where(function ($subQuery) {
                    $subQuery->whereHas('organization', function ($orgQuery) {
                        $orgQuery->where('name', 'like', '%' . $this->search . '%');
                    });
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.medications.expiring', [
            'expiring' => $expiring,
            'isSuperAdmin' => $this->isSuperAdmin,
        ]);
    }
}
