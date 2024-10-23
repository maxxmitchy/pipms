<?php

namespace App\Livewire\Inventory;

use App\Models\Inventory;
use App\Models\Organization;
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

    protected $queryString = ['searchTerm', 'lowStockOnly'];

    public function mount($organization)
    {
        $this->organizationId = $organization;
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
        $this->dispatch('openTransferModal', inventoryId: $inventoryId);
    }

    public function exportLowStock($format, ExportService $exportService)
    {
        $path = $exportService->exportLowStock($this->organizationId, $format);

        return response()->download($path)->deleteFileAfterSend();
    }
}
