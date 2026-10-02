<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Actions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;

class TransaksiStokPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-arrows-right-left';
    protected static ?string $navigationLabel = 'Transaksi';
    protected static ?string $title = 'Transaksi Stok';
    protected static ?int $navigationSort = 3;
    protected static bool $shouldRegisterNavigation = false;

    
    protected ?string $subheading = 'Rekam pergerakan barang masuk dan keluar gudang secara real-time.';
    
    protected string $view = 'filament.pages.transaksi-stok-page';

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('export_csv')
                ->label('Export CSV')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('gray')
                ->action(function () {
                    $in = \App\Models\StockInTransaction::with('product')->get()->map(function($i) { $i->tipe = 'MASUK'; return $i; });
                    $out = \App\Models\StockOutTransaction::with('product')->get()->map(function($i) { $i->tipe = 'KELUAR'; return $i; });
                    $all = $in->concat($out)->sortByDesc('created_at');
                    
                    $csv = "Waktu,Produk,Tipe,Jumlah,Keterangan\n";
                    foreach($all as $tx) {
                        $nama = $tx->product->nama ?? '';
                        $keterangan = $tx->keterangan ?? '';
                        $csv .= "\"{$tx->created_at->format('Y-m-d H:i:s')}\",\"{$nama}\",\"{$tx->tipe}\",\"{$tx->jumlah}\",\"{$keterangan}\"\n";
                    }
                    
                    \Filament\Notifications\Notification::make()
                        ->success()
                        ->title('Laporan CSV Berhasil Diunduh')
                        ->body('Data riwayat transaksi stok telah diekspor ke format CSV.')
                        ->sendToDatabase(auth()->user())
                        ->send();

                    return response()->streamDownload(function () use ($csv) {
                        echo $csv;
                    }, 'transaksi_stok_' . date('Y_m_d') . '.csv');
                }),
        ];
    }

    public ?array $data = [];
    public $showAllRiwayat = false;

    public function toggleRiwayat()
    {
        $this->showAllRiwayat = !$this->showAllRiwayat;
    }

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(\Filament\Schemas\Schema $form): \Filament\Schemas\Schema
    {
        return $form
            ->schema([
                \Filament\Schemas\Components\Tabs::make('Tipe Transaksi')
                    ->tabs([
                        \Filament\Schemas\Components\Tabs\Tab::make('Stok Masuk')
                            ->schema([
                                \Filament\Forms\Components\DatePicker::make('tanggal_transaksi')
                                    ->label('TANGGAL TRANSAKSI')
                                    ->default(now())
                                    ->required(),
                                \Filament\Forms\Components\Select::make('product_id')
                                    ->label('PILIH PRODUK')
                                    ->options(\App\Models\Product::all()->pluck('nama', 'id'))
                                    ->searchable(),
                                \Filament\Schemas\Components\Grid::make(2)
                                    ->schema([
                                        \Filament\Forms\Components\TextInput::make('jumlah')
                                            ->label('JUMLAH (QTY)')
                                            ->numeric(),
                                        \Filament\Forms\Components\TextInput::make('satuan')
                                            ->label('SATUAN')
                                            ->default('Pcs')
                                            ->disabled(),
                                    ]),
                                \Filament\Forms\Components\Textarea::make('keterangan')
                                    ->label('SUMBER BARANG')
                                    ->placeholder('Contoh: Vendor Supplier A / Retur Customer...')
                                    ->rows(3),
                            ]),
                        \Filament\Schemas\Components\Tabs\Tab::make('Stok Keluar')
                            ->schema([
                                \Filament\Forms\Components\DatePicker::make('tanggal_transaksi_keluar')
                                    ->label('TANGGAL TRANSAKSI')
                                    ->default(now())
                                    ->required(),
                                \Filament\Forms\Components\Select::make('product_id_keluar')
                                    ->label('PILIH PRODUK')
                                    ->options(\App\Models\Product::all()->pluck('nama', 'id'))
                                    ->searchable(),
                                \Filament\Schemas\Components\Grid::make(2)
                                    ->schema([
                                        \Filament\Forms\Components\TextInput::make('jumlah_keluar')
                                            ->label('JUMLAH (QTY)')
                                            ->numeric()
                                            ->rule(function ($get) {
                                                return function (string $attribute, $value, \Closure $fail) use ($get) {
                                                    $product = \App\Models\Product::find($get('product_id_keluar'));
                                                    if ($product && $value > $product->stok_aktual) {
                                                        $fail("Jumlah melebihi stok yang tersedia ({$product->stok_aktual}).");
                                                    }
                                                };
                                            }),
                                        \Filament\Forms\Components\TextInput::make('satuan_keluar')
                                            ->label('SATUAN')
                                            ->default('Pcs')
                                            ->disabled(),
                                    ]),
                                \Filament\Forms\Components\Textarea::make('keterangan_keluar')
                                    ->label('TUJUAN BARANG')
                                    ->placeholder('Contoh: Penjualan Toko / Pengiriman Cabang...')
                                    ->rows(3),
                            ]),
                    ])
                    ->columnSpan('full'),
            ])
            ->statePath('data');
    }

    public function simpanTransaksi(): void
    {
        $data = $this->form->getState();
        $saved = false;

        if (!empty($data['product_id']) && !empty($data['jumlah'])) {
            \App\Models\StockInTransaction::create([
                'product_id' => $data['product_id'],
                'jumlah' => $data['jumlah'],
                'keterangan' => $data['keterangan'] ?? '',
                'created_at' => !empty($data['tanggal_transaksi']) ? \Carbon\Carbon::parse($data['tanggal_transaksi']) : now(),
                'tanggal' => !empty($data['tanggal_transaksi']) ? \Carbon\Carbon::parse($data['tanggal_transaksi'])->toDateString() : now()->toDateString(),
            ]);
            $saved = true;
        }

        if (!empty($data['product_id_keluar']) && !empty($data['jumlah_keluar'])) {
            \App\Models\StockOutTransaction::create([
                'product_id' => $data['product_id_keluar'],
                'jumlah' => $data['jumlah_keluar'],
                'keterangan' => $data['keterangan_keluar'] ?? '',
                'created_at' => !empty($data['tanggal_transaksi_keluar']) ? \Carbon\Carbon::parse($data['tanggal_transaksi_keluar']) : now(),
                'tanggal' => !empty($data['tanggal_transaksi_keluar']) ? \Carbon\Carbon::parse($data['tanggal_transaksi_keluar'])->toDateString() : now()->toDateString(),
            ]);
            $saved = true;
        }

        if ($saved) {
            \Filament\Notifications\Notification::make()->success()->title('Transaksi berhasil direkam!')->sendToDatabase(auth()->user())->send();
            $this->form->fill();
        } else {
            \Filament\Notifications\Notification::make()->danger()->title('Harap isi produk dan jumlah!')->send();
        }
    }

    public function getTotalMasukHariIniProperty()
    {
        return \App\Models\StockInTransaction::whereDate('created_at', today())->sum('jumlah');
    }

    public function getTotalMasukHariIniListProperty()
    {
        $products = \App\Models\StockInTransaction::whereDate('created_at', today())->with('product')->get()->pluck('product.nama')->filter()->unique();
        return $this->formatTooltipList($products);
    }

    public function getTotalKeluarHariIniProperty()
    {
        return \App\Models\StockOutTransaction::whereDate('created_at', today())->sum('jumlah');
    }

    public function getTotalKeluarHariIniListProperty()
    {
        $products = \App\Models\StockOutTransaction::whereDate('created_at', today())->with('product')->get()->pluck('product.nama')->filter()->unique();
        return $this->formatTooltipList($products);
    }

    public function getSisaSkuKritisProperty()
    {
        return \App\Models\Product::where('status_stok', 'KRITIS')->count();
    }

    public function getSisaSkuKritisListProperty()
    {
        $products = \App\Models\Product::where('status_stok', 'KRITIS')->pluck('nama');
        return $this->formatTooltipList($products);
    }

    protected function formatTooltipList($products)
    {
        if ($products->isEmpty()) {
            return 'Tidak ada produk';
        }
        $list = $products->take(15)->implode("\n- ");
        $result = "- " . $list;
        if ($products->count() > 15) {
            $result .= "\n... dan " . ($products->count() - 15) . " lainnya";
        }
        return $result;
    }

    public function getListTotalMasukProperty()
    {
        return \App\Models\StockInTransaction::whereDate('created_at', today())
            ->with('product')
            ->get()
            ->map(fn($t) => $t->product)
            ->filter()
            ->unique('id')
            ->values();
    }

    public function getListTotalKeluarProperty()
    {
        return \App\Models\StockOutTransaction::whereDate('created_at', today())
            ->with('product')
            ->get()
            ->map(fn($t) => $t->product)
            ->filter()
            ->unique('id')
            ->values();
    }

    public function getListSisaSkuKritisProperty()
    {
        return \App\Models\Product::where('status_stok', 'KRITIS')->get();
    }

    public function getRiwayatTransaksiProperty()
    {
        $in = \App\Models\StockInTransaction::with('product')->latest()->get()->map(function($i) { $i->tipe = 'MASUK'; return $i; });
        $out = \App\Models\StockOutTransaction::with('product')->latest()->get()->map(function($i) { $i->tipe = 'KELUAR'; return $i; });
        $all = $in->concat($out)->sortByDesc('created_at');
        
        return $this->showAllRiwayat ? $all : $all->take(5);
    }

    public function clearRiwayatAction(): \Filament\Actions\Action
    {
        return \Filament\Actions\Action::make('clearRiwayat')
            ->label('Clear Semua Riwayat')
            ->color('danger')
            ->requiresConfirmation()
            ->modalHeading('Bersihkan Riwayat Transaksi')
            ->modalDescription('Yakin ingin membersihkan semua data riwayat transaksi? Tindakan ini tidak bisa dibatalkan.')
            ->modalSubmitActionLabel('Ya, bersihkan')
            ->action(function () {
                \App\Models\StockInTransaction::truncate();
                \App\Models\StockOutTransaction::truncate();
                \Filament\Notifications\Notification::make()->success()->title('Riwayat berhasil dibersihkan!')->send();
                $this->showAllRiwayat = false;
            });
    }

    public function clearRiwayat()
    {
        \App\Models\StockInTransaction::truncate();
        \App\Models\StockOutTransaction::truncate();
        \Filament\Notifications\Notification::make()->success()->title('Riwayat berhasil dibersihkan!')->send();
    }
}
