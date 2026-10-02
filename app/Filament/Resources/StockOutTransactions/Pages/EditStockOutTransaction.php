<?php

namespace App\Filament\Resources\StockOutTransactions\Pages;

use App\Filament\Resources\StockOutTransactions\StockOutTransactionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditStockOutTransaction extends EditRecord
{
    protected static string $resource = StockOutTransactionResource::class;

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
