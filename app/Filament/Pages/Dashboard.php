<?php

namespace App\Filament\Pages;

use Filament\Actions\Action;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    public function getColumns(): int | array
    {
        return [
            'default' => 1,
            'md' => 2,
            'xl' => 3,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('ekspor')
                ->label('Ekspor Laporan')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('gray')
                ->url(route('filament.admin.pages.laporan-page')),
            
            Action::make('tambah_produk')
                ->label('Tambah Produk')
                ->icon('heroicon-o-plus')
                ->color('gray')
                ->url(fn (): string => \App\Filament\Resources\Products\ProductResource::getUrl('create')),
        ];
    }
}
