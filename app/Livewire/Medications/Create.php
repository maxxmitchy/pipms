<?php

namespace App\Livewire\Medications;

use App\Models\Brand;
use App\Models\Medication;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Create extends Component
{
    public $name = '';

    public $description = '';

    public $dosage = '';

    public $manufacturer = '';

    public $brand_id = '';

    public $brands;

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'dosage' => 'required|string|max:255',
        'manufacturer' => 'required|string|max:255',
        'brand_id' => 'required|exists:brands,id',
    ];

    protected $listeners = ['brandCreated'];

    public function brandCreated($brandId)
    {
        $this->brand_id = $brandId;
        $this->brands = Brand::all(); // Refresh the brands list
    }

    public function mount()
    {
        $this->brands = Brand::all();

        $this->authorize('create', Medication::class);
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.medications.create', [
            'brands' => $this->brands,
        ]);
    }

    public function save()
    {
        $this->validate();

        Medication::create([
            'name' => $this->name,
            'description' => $this->description,
            'dosage' => $this->dosage,
            'manufacturer' => $this->manufacturer,
            'brand_id' => $this->brand_id,
        ]);

        session()->flash('message', 'Medication created successfully.');

        return redirect()->route('medications.index');
    }
}
