<?php

namespace App\Livewire\Transfers;

use App\Models\Transfer;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class TransferList extends Component
{
    use WithPagination;

    public $search = '';

    public $sortField = 'created_at';

    public $sortDirection = 'desc';

    protected $queryString = ['search', 'sortField', 'sortDirection'];

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    #[Layout('layouts.app')]
    public function render()
    {
        $transfers = Transfer::with(['fromInventory', 'toInventory', 'fromOrganization', 'toOrganization', 'fromUser', 'toUser'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->whereHas('fromOrganization', function ($q) {
                        $q->where('name', 'like', '%'.$this->search.'%');
                    })
                        ->orWhereHas('toOrganization', function ($q) {
                            $q->where('name', 'like', '%'.$this->search.'%');
                        })
                        ->orWhereHas('fromUser', function ($q) {
                            $q->where('name', 'like', '%'.$this->search.'%');
                        })
                        ->orWhereHas('toUser', function ($q) {
                            $q->where('name', 'like', '%'.$this->search.'%');
                        });
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.transfers.transfer-list', compact('transfers'));
    }
}
