<?php

namespace App\Services;

use App\Models\Inventory;
use Spatie\SimpleExcel\SimpleExcelWriter;

class ExportService
{
    public function exportLowStock($organizationId, $format)
    {
        $fileName = 'low_stock_'.now()->format('Y-m-d_H-i-s').'.'.$format;
        $path = storage_path('app/public/'.$fileName);

        $writer = SimpleExcelWriter::create($path)
            ->addHeader(['Medication Name', 'Quantity Left', 'Reorder quantity']);

        Inventory::where('organization_id', $organizationId)
            ->whereRaw('quantity <= reorder_level')
            ->with('medication')
            ->chunk(1000, function ($inventories) use ($writer) {
                foreach ($inventories as $inventory) {
                    $writer->addRow([
                        $inventory->medication->name,
                        $inventory->quantity,
                        $inventory->reorder_quantity,
                        // \Carbon\Carbon::parse($inventory->expiration_date)->format('Y-m-d'),
                        // $inventory->batch_number,
                    ]);
                }
            });

        return $path;
    }
}
