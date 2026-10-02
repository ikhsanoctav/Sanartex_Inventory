<?php

namespace App\Filament\Resources\StockInTransactions\Pages;

use App\Filament\Resources\StockInTransactions\StockInTransactionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListStockInTransactions extends ListRecords
{
    protected static string $resource = StockInTransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
