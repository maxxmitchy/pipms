<?php

namespace App\Livewire\Inventory;

use App\Models\Inventory;
use App\Models\Medication;
use App\Models\Organization;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Edit extends Component
{
    public Inventory $inventory;

    public $medications;

    public $organizations;

    public $quantity;

    public $expiration_date;

    public $batch_number;

    public $reorder_level;

    public $medication_id;

    public $organization_id;

    protected $rules = [
        'quantity' => 'required|integer|min:0',
        'expiration_date' => 'required|date',
        'batch_number' => 'required|string|max:255',
        'reorder_level' => 'required|integer|min:0',
        'medication_id' => 'required|exists:medications,id',
        'organization_id' => 'required|exists:organizations,id',
    ];

    public function mount(Inventory $inventory)
    {
        $this->inventory = $inventory;
        $this->medications = Medication::all();
        $this->organizations = Organization::all();

        $this->quantity = $inventory->quantity;
        $this->expiration_date = $inventory->expiration_date;
        $this->batch_number = $inventory->batch_number;
        $this->reorder_level = $inventory->reorder_level;
        $this->medication_id = $inventory->medication_id;
        $this->organization_id = $inventory->organization_id;
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.inventory.edit');
    }

    public function save()
    {
        $this->validate();

        $this->inventory->update([
            'quantity' => $this->quantity,
            'expiration_date' => $this->expiration_date,
            'batch_number' => $this->batch_number,
            'reorder_level' => $this->reorder_level,
            'medication_id' => $this->medication_id,
            'organization_id' => $this->organization_id,
        ]);

        session()->flash('message', 'Inventory item updated successfully.');

        return redirect()->route('inventory.index', ['organization' => $this->organization_id]);
    }
}
