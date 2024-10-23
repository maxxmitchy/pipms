<?php

namespace App\Livewire\Prescriptions;

use App\Models\Medication;
use App\Models\Organization;
use App\Models\Prescription;
use Livewire\Component;

class Edit extends Component
{
    public Prescription $prescription;

    public $medications;

    public $organizations;

    protected $rules = [
        'prescription.patient_name' => 'required|string|max:255',
        'prescription.dosage' => 'required|string|max:255',
        'prescription.frequency' => 'required|string|max:255',
        'prescription.duration' => 'required|string|max:255',
        'prescription.notes' => 'nullable|string',
        'prescription.medication_id' => 'required|exists:medications,id',
        'prescription.organization_id' => 'required|exists:organizations,id',
    ];

    public function mount(Prescription $prescription)
    {
        $this->prescription = $prescription;
        $this->medications = Medication::all();
        $this->organizations = Organization::all();
    }

    public function render()
    {
        return view('livewire.prescriptions.edit');
    }

    public function save()
    {
        $this->validate();

        $this->prescription->save();

        session()->flash('message', 'Prescription updated successfully.');

        return redirect()->route('prescriptions.index');
    }
}
