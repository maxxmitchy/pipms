<?php

namespace App\Livewire\Inventory;

use App\Models\Inventory;
use App\Models\Organization;
use App\Models\User;
use App\Services\ExportService;
use App\Services\InventoryService;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $organizationId;
    public $searchTerm = '';
    public $lowStockOnly = false;
    public $selectedItems = [];
    public $requiredAmounts = [];
    public $showTransferModal = false;
    public $inventoryId;
    public $quantity;
    public $toOrganizationId;
    public $toUserId;
    public $organizations;
    public $users;
    public $showBulkTransferModal = false;
    public $bulkTransferData = [];
    public $bulkToOrganizationId;
    public $bulkToUserId;
    public $bulkTransferUsers = [];

    protected $queryString = ['searchTerm', 'lowStockOnly'];

    public function mount($organization)
    {
        $this->organizationId = $organization;
        $this->organizations = Organization::where('id', '!=', $this->organizationId)->get();
    }

    #[Layout('layouts.app')]
    public function render(InventoryService $inventoryService)
    {
        $organization = Organization::findOrFail($this->organizationId);
        $inventoryQuery = $inventoryService->getInventoryForOrganization($organization);

        if ($this->searchTerm) {
            $inventoryQuery = $inventoryQuery->whereHas('medication', function ($query) {
                $query->where('name', 'like', '%'.$this->searchTerm.'%');
            });
        }

        if ($this->lowStockOnly) {
            $inventoryQuery = $inventoryQuery->where(function ($query) use ($inventoryService) {
                $inventory = $query->get();
                $lowStockIds = $inventory->filter(function ($item) use ($inventoryService) {
                    return $inventoryService->checkLowStock($item);
                })->pluck('id');
                $query->whereIn('id', $lowStockIds);
            });
        }

        $inventory = $inventoryQuery->paginate(10);

        return view('livewire.inventory.index', [
            'inventory' => $inventory,
            'organization' => $organization,
            'inventoryService' => $inventoryService,
            'selectedItems' => $this->selectedItems,
            'requiredAmounts' => $this->requiredAmounts,
            'organizations' => $this->organizations,
            'users' => $this->users,
            'bulkTransferUsers' => $this->bulkTransferUsers,
        ]);
    }

    public function updateQuantity($inventoryId, $newQuantity, InventoryService $inventoryService)
    {
        $inventory = Inventory::findOrFail($inventoryId);
        $inventoryService->updateInventoryQuantity($inventory, $newQuantity);
        $this->dispatch('inventoryUpdated');
    }

    public function initiateTransfer($inventoryId)
    {
        $this->inventoryId = $inventoryId;
        $this->showTransferModal = true;
    }

    public function updatedToOrganizationId($value)
    {
        $this->users = User::where('organization_id', $value)->get();
        $this->toUserId = null;
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
            $this->showTransferModal = false;
            $this->reset(['quantity', 'toOrganizationId', 'toUserId']);
        } catch (\InvalidArgumentException $e) {
            $this->addError('quantity', $e->getMessage());
        }
    }

    public function exportLowStock($format, ExportService $exportService)
    {
        $path = $exportService->exportLowStock($this->organizationId, $format);

        return response()->download($path)->deleteFileAfterSend();
    }

    public function bulkTransfer()
    {
        if (empty($this->selectedItems)) {
            $this->addError('bulkTransfer', 'Please select at least one item to transfer.');
            return;
        }

        foreach ($this->selectedItems as $itemId) {
            if (!isset($this->requiredAmounts[$itemId]) || $this->requiredAmounts[$itemId] <= 0) {
                $this->addError('bulkTransfer', 'Please specify a valid required amount for all selected items.');
                return;
            }
        }

        $this->bulkTransferData = [];
        foreach ($this->selectedItems as $itemId) {
            $this->bulkTransferData[] = [
                'inventory_id' => $itemId,
                'required_amount' => $this->requiredAmounts[$itemId],
            ];
        }

        $this->showBulkTransferModal = true;
    }

    public function updatedBulkToOrganizationId($value)
    {
        if ($value) {
            $this->bulkTransferUsers = User::where('organization_id', $value)->get();
        } else {
            $this->bulkTransferUsers = [];
        }
        $this->bulkToUserId = null;
    }

    public function submitBulkTransfer(InventoryService $inventoryService)
    {
        $this->validate([
            'bulkToOrganizationId' => 'required|exists:organizations,id',
            'bulkToUserId' => 'required|exists:users,id',
        ]);

        $toOrganization = Organization::findOrFail($this->bulkToOrganizationId);
        $fromUser = auth()->user();
        $toUser = User::findOrFail($this->bulkToUserId);

        foreach ($this->bulkTransferData as $transferItem) {
            $fromInventory = Inventory::findOrFail($transferItem['inventory_id']);
            $quantity = $transferItem['required_amount'];

            try {
                $inventoryService->transferInventory($fromInventory, $toOrganization, $quantity, $fromUser, $toUser);
            } catch (\InvalidArgumentException $e) {
                $this->addError('bulkTransfer', $e->getMessage());
                return;
            }
        }

        $this->dispatch('inventoryUpdated');
        $this->showBulkTransferModal = false;
        $this->reset(['selectedItems', 'requiredAmounts', 'bulkTransferData', 'bulkToOrganizationId', 'bulkToUserId']);
    }
}
