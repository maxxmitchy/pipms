<?php

namespace App\Services;

use App\Models\Medication;
use Illuminate\Database\Eloquent\Collection;

class MedicationService
{
    public function getAllMedications(): Collection
    {
        return Medication::all();
    }

    public function createMedication(array $data): Medication
    {
        return Medication::create($data);
    }

    public function updateMedication(Medication $medication, array $data): bool
    {
        return $medication->update($data);
    }

    public function deleteMedication(Medication $medication): bool
    {
        return $medication->delete();
    }
}
