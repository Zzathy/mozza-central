<?php

namespace App\Filament\Resources\StockEntries\Pages;

use App\Filament\Resources\StockEntries\StockEntryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateStockEntry extends CreateRecord
{
    protected static string $resource = StockEntryResource::class;

    protected function afterCreate(): void
    {
        $batches = $this->record->productBatches;

        foreach ($batches as $batch) {
            $batch->product->update([
                'cost_price' => $batch->buy_price,
            ]);
        }
    }
}
