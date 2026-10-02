<?php

namespace App\Filament\Resources\StockOutTransactions\Pages;

use App\Filament\Resources\StockOutTransactions\StockOutTransactionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateStockOutTransaction extends CreateRecord
{
    protected static string $resource = StockOutTransactionResource::class;

    protected function getCreatedNotification(): ?\Filament\Notifications\Notification
    {
        return parent::getCreatedNotification()?->sendToDatabase(auth()->user());
    }
}
