<?php

namespace App\Filament\GlobalSearch;

use Filament\GlobalSearch\Providers\DefaultGlobalSearchProvider;
use Filament\GlobalSearch\GlobalSearchResult;
use Filament\GlobalSearch\GlobalSearchResults;
use Filament\Facades\Filament;

class CustomGlobalSearchProvider extends DefaultGlobalSearchProvider
{
    public function getResults(string $query): ?GlobalSearchResults
    {
        // Panggil default results terlebih dahulu agar Resources tetap bisa dicari
        $results = parent::getResults($query);

        if (! $results) {
            $results = GlobalSearchResults::make();
        }

        $menus = [
            ['title' => 'Dashboard', 'url' => Filament::getUrl()],
            ['title' => 'Produk', 'url' => \App\Filament\Resources\Products\ProductResource::getUrl('index')],
            ['title' => 'Transaksi', 'url' => \App\Filament\Pages\TransaksiStokPage::getUrl()],
            ['title' => 'Rekap Stok', 'url' => \App\Filament\Pages\RekapStokPage::getUrl()],
            ['title' => 'Laporan', 'url' => \App\Filament\Pages\LaporanPage::getUrl()],
        ];

        foreach ($menus as $menu) {
            if (stripos($menu['title'], $query) !== false) {
                // Menambahkan menu yang cocok ke dalam kategori "Menu Navigasi"
                $results->category('Menu Navigasi', [
                    new GlobalSearchResult(
                        title: $menu['title'],
                        url: $menu['url']
                    )
                ]);
            }
        }

        return $results;
    }
}
