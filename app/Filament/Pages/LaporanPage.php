<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanPage extends Page
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationLabel = 'Laporan';
    protected static ?string $title = 'Preview Laporan';
    protected ?string $subheading = 'Review and print your monthly inventory performance.';
    protected static ?int $navigationSort = 10;

    protected string $view = 'filament.pages.laporan-page';

    public ?string $startDate = null;
    public ?string $endDate = null;

    protected $queryString = [
        'startDate' => ['except' => ''],
        'endDate' => ['except' => ''],
    ];

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('download_pdf')
                ->label('Download PDF')
                ->color('gray')
                ->icon('heroicon-o-arrow-down-tray')
                ->action(function () {
                    $start = $this->startDate ? \Carbon\Carbon::parse($this->startDate)->startOfDay() : now()->startOfMonth();
                    $end = $this->endDate ? \Carbon\Carbon::parse($this->endDate)->endOfDay() : now()->endOfMonth();

                    $products = Product::all()->map(function ($product) use ($start, $end) {
                        $masuk = $product->stockInTransactions()->whereBetween('created_at', [$start, $end])->sum('jumlah');
                        $keluar = $product->stockOutTransactions()->whereBetween('created_at', [$start, $end])->sum('jumlah');
                        $akhir = $product->stok_aktual;
                        $awal = $akhir - $masuk + $keluar; 
                        
                        $product->masuk = $masuk;
                        $product->keluar = $keluar;
                        $product->awal = $awal;
                        $product->akhir = $akhir;
                        
                        return $product;
                    });

                    $pdf = Pdf::loadView('pdf.rekap-stok', [
                        'products' => $products,
                        'startDate' => $start,
                        'endDate' => $end,
                    ]);
                    
                    \Filament\Notifications\Notification::make()
                        ->title('Dokumen laporan berhasil diunduh')
                        ->success()
                        ->sendToDatabase(auth()->user())
                        ->send();
                    
                    return response()->streamDownload(function () use ($pdf) {
                        echo $pdf->output();
                    }, 'Laporan_Rekap_Stok_' . date('Y_m_d') . '.pdf');
                }),
            \Filament\Actions\Action::make('cetak_laporan')
                ->label('Cetak Laporan')
                ->color('gray')
                ->icon('heroicon-o-printer')
                ->action(function () {
                    // Handled by extraAttributes
                })
                ->extraAttributes([
                    'style' => 'background-color: #111827; color: white;',
                    'onclick' => 'window.print()',
                ]),
        ];
    }

    protected function getViewData(): array
    {
        $start = $this->startDate ? \Carbon\Carbon::parse($this->startDate)->startOfDay() : now()->startOfMonth();
        $end = $this->endDate ? \Carbon\Carbon::parse($this->endDate)->endOfDay() : now()->endOfMonth();

        $products = Product::all()->map(function ($product) use ($start, $end) {
            $masuk = $product->stockInTransactions()->whereBetween('created_at', [$start, $end])->sum('jumlah');
            $keluar = $product->stockOutTransactions()->whereBetween('created_at', [$start, $end])->sum('jumlah');
            $akhir = $product->stok_aktual;
            $awal = $akhir - $masuk + $keluar; 
            
            $product->masuk = $masuk;
            $product->keluar = $keluar;
            $product->awal = $awal;
            $product->akhir = $akhir;
            
            return $product;
        });

        $periodeStr = $start->translatedFormat('d M Y') . ' - ' . $end->translatedFormat('d M Y');

        return [
            'products' => $products,
            'periodeStr' => $periodeStr,
        ];
    }
}
