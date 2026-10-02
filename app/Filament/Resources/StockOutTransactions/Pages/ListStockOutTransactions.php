<?php

namespace App\Filament\Resources\StockOutTransactions\Pages;

use App\Filament\Resources\StockOutTransactions\StockOutTransactionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListStockOutTransactions extends ListRecords
{
    protected static string $resource = StockOutTransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
