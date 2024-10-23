<?php

namespace App\Livewire\Prescriptions;

use App\Models\Medication;
use App\Models\Organization;
use App\Models\Prescription;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Create extends Component
{
    public $patient_name = '';

    public $dosage = '';

    public $frequency = '';

    public $duration = '';

    public $notes = '';

    public $medication_id = '';

    public $organization_id = '';

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.prescriptions.create', [
            'medications' => Medication::all(),
            'organizations' => Organization::all(),
        ]);
    }

    public function save()
    {
        $validatedData = $this->validate([
            'patient_name' => 'required|string|max:255',
            'dosage' => 'required|string|max:255',
            'frequency' => 'required|string|max:255',
            'duration' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'medication_id' => 'required|exists:medications,id',
            'organization_id' => 'required|exists:organizations,id',
        ]);

        $validatedData['user_id'] = auth()->id();

        Prescription::create($validatedData);

        session()->flash('message', 'Prescription created successfully.');

        return redirect()->route('prescriptions.index');
    }
}
