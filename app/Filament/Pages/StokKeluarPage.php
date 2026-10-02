<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;

class StokKeluarPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-arrow-up-circle';
    protected static ?string $navigationLabel = 'Stok Keluar';
    protected static ?string $title = 'Stok Keluar';
    protected static ?int $navigationSort = 4;

    protected ?string $subheading = 'Rekam pengeluaran barang dari gudang.';

    public static function getNavigationGroup(): ?string
    {
        return 'Transaksi';
    }

    public static function getNavigationGroupSort(): ?int
    {
        return 3;
    }

    protected string $view = 'filament.pages.stok-keluar-page';

    public ?array $data = [];
    public $showAllRiwayat = false;

    public function mount(): void
    {
        $this->form->fill();
    }

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('export_csv_keluar')
                ->label('Export CSV')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('gray')
                ->action(function () {
                    $all = \App\Models\StockOutTransaction::with('product')->latest()->get();

                    $csv = "Waktu,Produk,Jumlah,Keterangan\n";
                    foreach ($all as $tx) {
                        $nama = $tx->product->nama ?? '';
                        $keterangan = $tx->keterangan ?? '';
                        $csv .= "\"{$tx->created_at->format('Y-m-d H:i:s')}\",\"{$nama}\",\"{$tx->jumlah}\",\"{$keterangan}\"\n";
                    }

                    \Filament\Notifications\Notification::make()
                        ->success()
                        ->title('CSV Stok Keluar Berhasil Diunduh')
                        ->sendToDatabase(auth()->user())
                        ->send();

                    return response()->streamDownload(function () use ($csv) {
                        echo $csv;
                    }, 'stok_keluar_' . date('Y_m_d') . '.csv');
                }),
        ];
    }

    public function form(\Filament\Schemas\Schema $form): \Filament\Schemas\Schema
    {
        return $form
            ->schema([
                \Filament\Forms\Components\DatePicker::make('tanggal_transaksi_keluar')
                    ->label('TANGGAL TRANSAKSI')
                    ->default(now())
                    ->required(),
                \Filament\Forms\Components\Select::make('product_id_keluar')
                    ->label('PILIH PRODUK')
                    ->options(\App\Models\Product::all()->pluck('nama', 'id'))
                    ->searchable()
                    ->required(),
                \Filament\Schemas\Components\Grid::make(2)
                    ->schema([
                        \Filament\Forms\Components\TextInput::make('jumlah_keluar')
                            ->label('JUMLAH (QTY)')
                            ->numeric()
                            ->required()
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
            ])
            ->statePath('data');
    }

    public function simpan(): void
    {
        $data = $this->form->getState();

        if (empty($data['product_id_keluar']) || empty($data['jumlah_keluar'])) {
            \Filament\Notifications\Notification::make()->danger()->title('Harap isi produk dan jumlah!')->send();
            return;
        }

        \App\Models\StockOutTransaction::create([
            'product_id' => $data['product_id_keluar'],
            'jumlah'     => $data['jumlah_keluar'],
            'keterangan' => $data['keterangan_keluar'] ?? '',
            'created_at' => !empty($data['tanggal_transaksi_keluar']) ? \Carbon\Carbon::parse($data['tanggal_transaksi_keluar']) : now(),
            'tanggal'    => !empty($data['tanggal_transaksi_keluar']) ? \Carbon\Carbon::parse($data['tanggal_transaksi_keluar'])->toDateString() : now()->toDateString(),
        ]);

        \Filament\Notifications\Notification::make()->success()->title('Stok keluar berhasil direkam!')->sendToDatabase(auth()->user())->send();
        $this->form->fill();
    }

    public function toggleRiwayat(): void
    {
        $this->showAllRiwayat = !$this->showAllRiwayat;
    }

    public function getTotalKeluarHariIniProperty()
    {
        return \App\Models\StockOutTransaction::whereDate('created_at', today())->sum('jumlah');
    }

    public function getSisaSkuKritisProperty()
    {
        return \App\Models\Product::where('status_stok', 'KRITIS')->count();
    }

    public function getRiwayatKeluarProperty()
    {
        $query = \App\Models\StockOutTransaction::with('product')->latest();
        return $this->showAllRiwayat ? $query->get() : $query->take(5)->get();
    }

    public function clearRiwayatAction(): \Filament\Actions\Action
    {
        return \Filament\Actions\Action::make('clearRiwayat')
            ->label('Clear Semua Riwayat')
            ->color('danger')
            ->requiresConfirmation()
            ->modalHeading('Bersihkan Riwayat Stok Keluar')
            ->modalDescription('Yakin ingin membersihkan semua data stok keluar? Tindakan ini tidak bisa dibatalkan.')
            ->modalSubmitActionLabel('Ya, bersihkan')
            ->action(function () {
                \App\Models\StockOutTransaction::truncate();
                \Filament\Notifications\Notification::make()->success()->title('Riwayat stok keluar berhasil dibersihkan!')->send();
                $this->showAllRiwayat = false;
            });
    }
}
