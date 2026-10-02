<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    protected function getCreatedNotification(): ?\Filament\Notifications\Notification
    {
        return \Filament\Notifications\Notification::make()
            ->success()
            ->title('Produk Baru Berhasil Dibuat')
            ->body("Produk '{$this->record->nama}' telah berhasil ditambahkan.")
            ->sendToDatabase(auth()->user());
    }
}
