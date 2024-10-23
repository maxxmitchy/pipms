<?php

namespace App\Livewire\Inventory;

use App\Models\Inventory;
use App\Models\Medication;
use App\Models\Organization;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Create extends Component
{
    public $quantity;

    public $expiration_date;

    public $batch_number;

    public $reorder_level;

    public $reorder_quantity;

    public $medication_id;

    public $organization_id;

    protected $rules = [
        'quantity' => 'required|integer|min:0',
        'expiration_date' => 'required|date',
        'batch_number' => 'required|string|max:255',
        'reorder_level' => 'required|integer|min:0',
        'reorder_quantity' => 'required|integer|min:0',
        'medication_id' => 'required|exists:medications,id',
        'organization_id' => 'required|exists:organizations,id',
    ];

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.inventory.create', [
            'medications' => Medication::all(),
            'organizations' => Organization::all(),
        ]);
    }

    public function save()
    {
        $this->validate();

        Inventory::create([
            'quantity' => $this->quantity,
            'expiration_date' => $this->expiration_date,
            'batch_number' => $this->batch_number,
            'reorder_level' => $this->reorder_level,
            'reorder_quantity' => $this->reorder_quantity,
            'medication_id' => $this->medication_id,
            'organization_id' => $this->organization_id,
        ]);

        session()->flash('message', 'Inventory item created successfully.');

        return redirect()->route('inventory.index', ['organization' => $this->organization_id]);
    }
}
