<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;

class StokMasukPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-arrow-down-circle';
    protected static ?string $navigationLabel = 'Stok Masuk';
    protected static ?string $title = 'Stok Masuk';
    protected static ?int $navigationSort = 3;

    protected ?string $subheading = 'Rekam penerimaan barang masuk ke gudang.';

    public static function getNavigationGroup(): ?string
    {
        return 'Transaksi';
    }

    public static function getNavigationGroupSort(): ?int
    {
        return 3;
    }

    protected string $view = 'filament.pages.stok-masuk-page';

    public ?array $data = [];
    public $showAllRiwayat = false;

    public function mount(): void
    {
        $this->form->fill();
    }

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('export_csv_masuk')
                ->label('Export CSV')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('gray')
                ->action(function () {
                    $all = \App\Models\StockInTransaction::with('product')->latest()->get();

                    $csv = "Waktu,Produk,Jumlah,Keterangan\n";
                    foreach ($all as $tx) {
                        $nama = $tx->product->nama ?? '';
                        $keterangan = $tx->keterangan ?? '';
                        $csv .= "\"{$tx->created_at->format('Y-m-d H:i:s')}\",\"{$nama}\",\"{$tx->jumlah}\",\"{$keterangan}\"\n";
                    }

                    \Filament\Notifications\Notification::make()
                        ->success()
                        ->title('CSV Stok Masuk Berhasil Diunduh')
                        ->sendToDatabase(auth()->user())
                        ->send();

                    return response()->streamDownload(function () use ($csv) {
                        echo $csv;
                    }, 'stok_masuk_' . date('Y_m_d') . '.csv');
                }),
        ];
    }

    public function form(\Filament\Schemas\Schema $form): \Filament\Schemas\Schema
    {
        return $form
            ->schema([
                \Filament\Forms\Components\DatePicker::make('tanggal_transaksi')
                    ->label('TANGGAL TRANSAKSI')
                    ->default(now())
                    ->required(),
                \Filament\Forms\Components\Select::make('product_id')
                    ->label('PILIH PRODUK')
                    ->options(\App\Models\Product::all()->pluck('nama', 'id'))
                    ->searchable()
                    ->required(),
                \Filament\Schemas\Components\Grid::make(2)
                    ->schema([
                        \Filament\Forms\Components\TextInput::make('jumlah')
                            ->label('JUMLAH (QTY)')
                            ->numeric()
                            ->required(),
                        \Filament\Forms\Components\TextInput::make('satuan')
                            ->label('SATUAN')
                            ->default('Pcs')
                            ->disabled(),
                    ]),
                \Filament\Forms\Components\Textarea::make('keterangan')
                    ->label('SUMBER BARANG')
                    ->placeholder('Contoh: Vendor Supplier A / Retur Customer...')
                    ->rows(3),
            ])
            ->statePath('data');
    }

    public function simpan(): void
    {
        $data = $this->form->getState();

        if (empty($data['product_id']) || empty($data['jumlah'])) {
            \Filament\Notifications\Notification::make()->danger()->title('Harap isi produk dan jumlah!')->send();
            return;
        }

        \App\Models\StockInTransaction::create([
            'product_id' => $data['product_id'],
            'jumlah'     => $data['jumlah'],
            'keterangan' => $data['keterangan'] ?? '',
            'created_at' => !empty($data['tanggal_transaksi']) ? \Carbon\Carbon::parse($data['tanggal_transaksi']) : now(),
            'tanggal'    => !empty($data['tanggal_transaksi']) ? \Carbon\Carbon::parse($data['tanggal_transaksi'])->toDateString() : now()->toDateString(),
        ]);

        \Filament\Notifications\Notification::make()->success()->title('Stok masuk berhasil direkam!')->sendToDatabase(auth()->user())->send();
        $this->form->fill();
    }

    public function toggleRiwayat(): void
    {
        $this->showAllRiwayat = !$this->showAllRiwayat;
    }

    public function getTotalMasukHariIniProperty()
    {
        return \App\Models\StockInTransaction::whereDate('created_at', today())->sum('jumlah');
    }

    public function getRiwayatMasukProperty()
    {
        $query = \App\Models\StockInTransaction::with('product')->latest();
        return $this->showAllRiwayat ? $query->get() : $query->take(5)->get();
    }

    public function clearRiwayatAction(): \Filament\Actions\Action
    {
        return \Filament\Actions\Action::make('clearRiwayat')
            ->label('Clear Semua Riwayat')
            ->color('danger')
            ->requiresConfirmation()
            ->modalHeading('Bersihkan Riwayat Stok Masuk')
            ->modalDescription('Yakin ingin membersihkan semua data stok masuk? Tindakan ini tidak bisa dibatalkan.')
            ->modalSubmitActionLabel('Ya, bersihkan')
            ->action(function () {
                \App\Models\StockInTransaction::truncate();
                \Filament\Notifications\Notification::make()->success()->title('Riwayat stok masuk berhasil dibersihkan!')->send();
                $this->showAllRiwayat = false;
            });
    }
}
