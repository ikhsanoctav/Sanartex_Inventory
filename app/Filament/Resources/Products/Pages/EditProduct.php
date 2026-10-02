<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->successNotification(
                    \Filament\Notifications\Notification::make()
                        ->success()
                        ->title('Produk Berhasil Dihapus')
                        ->body('Data produk telah berhasil dihapus secara permanen.')
                ),
        ];
    }

    protected function getSavedNotification(): ?\Filament\Notifications\Notification
    {
        return \Filament\Notifications\Notification::make()
            ->success()
            ->title('Produk Berhasil Diperbarui')
            ->body("Data produk '{$this->record->nama}' telah berhasil disimpan.")
            ->sendToDatabase(auth()->user());
    }
}
