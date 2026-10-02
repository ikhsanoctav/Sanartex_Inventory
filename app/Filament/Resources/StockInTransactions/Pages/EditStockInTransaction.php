<?php

namespace App\Filament\Resources\StockInTransactions\Pages;

use App\Filament\Resources\StockInTransactions\StockInTransactionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditStockInTransaction extends EditRecord
{
    protected static string $resource = StockInTransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getSavedNotification(): ?\Filament\Notifications\Notification
    {
        return parent::getSavedNotification()?->sendToDatabase(auth()->user());
    }
}
