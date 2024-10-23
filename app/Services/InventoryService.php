<?php

namespace App\Services;

use App\Models\Inventory;
use App\Models\Organization;
use App\Models\Transfer;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class InventoryService
{
    public function getInventoryForOrganization(Organization $organization)
    {
        return Inventory::with('medication')
            ->where('organization_id', $organization->id);
    }

    public function updateInventoryQuantity(Inventory $inventory, int $newQuantity): void
    {
        $inventory->update(['quantity' => $newQuantity]);
        $this->checkLowStock($inventory);
    }

    public function checkLowStock(Inventory $inventory): bool
    {
        return $inventory->quantity <= $inventory->reorder_level;
    }

    public function transferInventory(Inventory $fromInventory, Organization $toOrganization, int $quantity, User $fromUser, User $toUser): void
    {
        if ($fromInventory->quantity < $quantity) {
            throw new \InvalidArgumentException('Insufficient inventory for transfer');
        }

        DB::transaction(function () use ($fromInventory, $toOrganization, $quantity, $fromUser, $toUser) {
            $this->updateInventoryQuantity($fromInventory, $fromInventory->quantity - $quantity);

            $toInventory = Inventory::firstOrCreate(
                [
                    'medication_id' => $fromInventory->medication_id,
                    'organization_id' => $toOrganization->id,
                ],
                [
                    'quantity' => 0,
                    'expiration_date' => $fromInventory->expiration_date,
                    'batch_number' => $fromInventory->batch_number,
                    'reorder_level' => $fromInventory->reorder_level,
                    'reorder_quantity' => $fromInventory->reorder_quantity,
                ]
            );

            $this->updateInventoryQuantity($toInventory, $toInventory->quantity + $quantity);

            // Create a new transfer record
            Transfer::create([
                'from_inventory_id' => $fromInventory->id,
                'to_inventory_id' => $toInventory->id,
                'from_organization_id' => $fromInventory->organization_id,
                'to_organization_id' => $toOrganization->id,
                'from_user_id' => $fromUser->id,
                'to_user_id' => $toUser->id,
                'quantity' => $quantity,
            ]);
        });
    }
}
