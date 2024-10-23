<?php

namespace Tests\Unit\Services;

use App\Models\Medication;
use App\Services\MedicationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MedicationServiceTest extends TestCase
{
    use RefreshDatabase;

    private MedicationService $medicationService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->medicationService = new MedicationService;
    }

    public function test_can_get_all_medications()
    {
        Medication::factory()->count(3)->create();

        $medications = $this->medicationService->getAllMedications();

        $this->assertCount(3, $medications);
    }

    public function test_can_create_medication()
    {
        $data = [
            'name' => 'Test Medication',
            'description' => 'Test Description',
            'dosage' => '10mg',
            'manufacturer' => 'Test Manufacturer',
        ];

        $medication = $this->medicationService->createMedication($data);

        $this->assertInstanceOf(Medication::class, $medication);
        $this->assertEquals($data['name'], $medication->name);
    }

    public function test_can_update_medication()
    {
        $medication = Medication::factory()->create();
        $newData = ['name' => 'Updated Medication'];

        $result = $this->medicationService->updateMedication($medication, $newData);

        $this->assertTrue($result);
        $this->assertEquals('Updated Medication', $medication->fresh()->name);
    }

    public function test_can_delete_medication()
    {
        $medication = Medication::factory()->create();

        $result = $this->medicationService->deleteMedication($medication);

        $this->assertTrue($result);
        $this->assertNull(Medication::find($medication->id));
    }
}
