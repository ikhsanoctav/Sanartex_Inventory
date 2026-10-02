<?php

namespace App\Filament\Resources\StockInTransactions\Pages;

use App\Filament\Resources\StockInTransactions\StockInTransactionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateStockInTransaction extends CreateRecord
{
    protected static string $resource = StockInTransactionResource::class;

    protected function getCreatedNotification(): ?\Filament\Notifications\Notification
    {
        return parent::getCreatedNotification()?->sendToDatabase(auth()->user());
    }
}
