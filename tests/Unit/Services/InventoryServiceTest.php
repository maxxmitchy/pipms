<?php

namespace Tests\Unit\Services;

use App\Models\Inventory;
use App\Models\Medication;
use App\Models\Organization;
use App\Services\InventoryService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InventoryServiceTest extends TestCase
{
    use RefreshDatabase;

    private InventoryService $inventoryService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->inventoryService = new InventoryService;
    }

    public function test_get_inventory_for_organization()
    {
        $organization = Organization::factory()->create();
        Inventory::factory()->count(3)->create(['organization_id' => $organization->id]);

        $inventory = $this->inventoryService->getInventoryForOrganization($organization);

        $this->assertCount(3, $inventory);
    }

    public function test_update_inventory_quantity()
    {
        $inventory = Inventory::factory()->create(['quantity' => 10]);

        $this->inventoryService->updateInventoryQuantity($inventory, 15);

        $this->assertEquals(15, $inventory->fresh()->quantity);
    }

    public function test_check_low_stock()
    {
        $inventory = Inventory::factory()->create(['quantity' => 5,   'reorder_level' => 10]);

        $isLowStock = $this->inventoryService->checkLowStock($inventory);

        $this->assertTrue($isLowStock);
    }

    public function test_transfer_inventory()
    {
        $fromOrganization = Organization::factory()->create();
        $toOrganization = Organization::factory()->create();
        $medication = Medication::factory()->create();

        $fromInventory = Inventory::factory()->create([
            'medication_id' => $medication->id,
            'organization_id' => $fromOrganization->id,
            'quantity' => 20,
        ]);

        $this->inventoryService->transferInventory($fromInventory, $toOrganization, 10);

        $this->assertEquals(10, $fromInventory->fresh()->quantity);

        $toInventory = Inventory::where('medication_id', $medication->id)
            ->where('organization_id', $toOrganization->id)
            ->first();

        $this->assertNotNull($toInventory);
        $this->assertEquals(10, $toInventory->quantity);
    }

    public function test_transfer_inventory_insufficient_quantity()
    {
        $fromOrganization = Organization::factory()->create();
        $toOrganization = Organization::factory()->create();
        $medication = Medication::factory()->create();

        $fromInventory = Inventory::factory()->create([
            'medication_id' => $medication->id,
            'organization_id' => $fromOrganization->id,
            'quantity' => 5,
        ]);

        $this->expectException(\InvalidArgumentException::class);

        $this->inventoryService->transferInventory($fromInventory, $toOrganization, 10);
    }
}
