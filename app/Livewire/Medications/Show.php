<?php

namespace App\Livewire\Medications;

use App\Models\Medication;
use App\Services\DrugInformationService;
use Livewire\Component;

class Show extends Component
{
    public Medication $medication;

    public $drugInfo;

    public function mount(Medication $medication, DrugInformationService $drugInfoService)
    {
        $this->medication = $medication;
        $this->drugInfo = $drugInfoService->getDrugInformation($medication->name);
    }

    public function render()
    {
        return view('livewire.medications.show');
    }
}
