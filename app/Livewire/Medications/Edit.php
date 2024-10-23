<?php

namespace App\Livewire\Medications;

use App\Models\Brand;
use App\Models\Medication;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Edit extends Component
{
    public $name;

    public $description;

    public $dosage;

    public $manufacturer;

    public $brand_id;

    public $medicationId;

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'dosage' => 'required|string|max:255',
        'manufacturer' => 'required|string|max:255',
        'brand_id' => 'required|exists:brands,id',
    ];

    public function mount(Medication $medication)
    {
        $this->authorize('update', $medication);
        $this->medicationId = $medication->id;
        $this->name = $medication->name;
        $this->description = $medication->description;
        $this->dosage = $medication->dosage;
        $this->manufacturer = $medication->manufacturer;
        $this->brand_id = $medication->brand_id;
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.medications.edit', [
            'brands' => Brand::all(),
        ]);
    }

    public function save()
    {
        $this->validate();

        $medication = Medication::findOrFail($this->medicationId);
        $medication->update([
            'name' => $this->name,
            'description' => $this->description,
            'dosage' => $this->dosage,
            'manufacturer' => $this->manufacturer,
            'brand_id' => $this->brand_id,
        ]);

        session()->flash('message', 'Medication updated successfully.');

        return redirect()->route('medications.index');
    }
}
