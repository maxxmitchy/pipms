<?php

namespace App\Livewire\Inventory;

use App\Models\Inventory;
use App\Models\Organization;
use App\Models\User;
use App\Services\InventoryService;
use Livewire\Component;

class TransferModal extends Component
{
    public $showModal = false;

    public $inventoryId;

    public $quantity;

    public $toOrganizationId;

    public $toUserId;

    public $users = [];

    protected $listeners = ['openTransferModal'];

    public function openTransferModal($inventoryId)
    {
        $this->inventoryId = $inventoryId;
        $this->showModal = true;
    }

    public function render()
    {
        $organizations = Organization::all();

        return view('livewire.inventory.transfer-modal', [
            'organizations' => $organizations,
        ]);
    }

    public function searchUsers($search)
    {
        $this->users = User::where('name', 'like', "%{$search}%")
            ->orWhere('email', 'like', "%{$search}%")
            ->limit(10)
            ->get()
            ->toArray();
    }

    public function transferInventory(InventoryService $inventoryService)
    {
        $this->validate([
            'quantity' => 'required|integer|min:1',
            'toOrganizationId' => 'required|exists:organizations,id',
            'toUserId' => 'required|exists:users,id',
        ]);

        $fromInventory = Inventory::findOrFail($this->inventoryId);
        $toOrganization = Organization::findOrFail($this->toOrganizationId);
        $fromUser = auth()->user();
        $toUser = User::findOrFail($this->toUserId);

        try {
            $inventoryService->transferInventory($fromInventory, $toOrganization, $this->quantity, $fromUser, $toUser);
            $this->dispatch('inventoryUpdated');
            $this->showModal = false;
        } catch (\InvalidArgumentException $e) {
            $this->addError('quantity', $e->getMessage());
        }
    }
}
