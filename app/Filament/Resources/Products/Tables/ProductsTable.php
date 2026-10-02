<?php

namespace App\Filament\Resources\Products\Tables;

use App\Models\Product;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('index')
                    ->label('ID')
                    ->rowIndex()
                    ->formatStateUsing(fn ($state) => 'PRD-' . str_pad($state, 3, '0', STR_PAD_LEFT)),
                TextColumn::make('id_db')
                    ->label('KODE DB')
                    ->getStateUsing(fn (Product $record) => 'PRD-' . str_pad($record->id, 3, '0', STR_PAD_LEFT))
                    ->searchable(query: fn ($query, $search) => $query->where('id', 'like', "%" . (int) str_replace('PRD-', '', $search) . "%"))
                    ->sortable(['id'])
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('nama')
                    ->label('PRODUK & KATEGORI')
                    ->weight('bold')
                    ->description(fn (Product $record): string => $record->kategori ?? 'Umum')
                    ->searchable(),
                TextColumn::make('satuan')
                    ->label('SATUAN'),
                TextColumn::make('stok_aktual')
                    ->label('STOK AKTUAL')
                    ->weight('bold')
                    ->color(fn ($state) => $state == 0 ? 'danger' : 'default')
                    ->sortable(),
                TextColumn::make('batas_minimum')
                    ->label('BATAS MIN/MAX')
                    ->formatStateUsing(fn (Product $record): string => "{$record->batas_minimum} / {$record->batas_maksimum}")
                    ->color('gray'),
                TextColumn::make('rata_rata_unit')
                    ->label('RATA-RATA / TRX')
                    ->getStateUsing(function (Product $record) {
                        $avg = $record->stockOutTransactions()
                            ->whereDate('tanggal', '>=', now()->subDays(30))
                            ->avg('jumlah') ?? 0;
                        return number_format($avg, 1) . ' unit';
                    })
                    ->color('gray'),
                TextColumn::make('status_stok')
                    ->label('STATUS STOK')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'NORMAL' => 'success',
                        'KRITIS' => 'warning',
                        'BERLEBIH' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->filters([
                SelectFilter::make('kategori')
                    ->label('Semua Kategori')
                    ->options([
                        'Kaos' => 'Kaos',
                        'Hoodie' => 'Hoodie',
                        'Setelan Anak' => 'Setelan Anak',
                    ]),
                SelectFilter::make('status_stok')
                    ->label('Semua Status')
                    ->options([
                        'NORMAL' => 'NORMAL',
                        'KRITIS' => 'KRITIS',
                        'BERLEBIH' => 'BERLEBIH',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->deselectRecordsAfterCompletion()
                        ->successNotification(
                            \Filament\Notifications\Notification::make()
                                ->success()
                                ->title('Produk Berhasil Dihapus')
                                ->body('Data produk terpilih telah berhasil dihapus secara permanen.')
                        ),
                ]),
            ]);
    }
}
