<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class ProductsAttentionWidget extends BaseWidget
{
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 'full';

    protected static ?string $heading = 'Produk Perlu Perhatian';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Product::query()->whereIn('status_stok', ['KRITIS', 'BERLEBIH'])
            )
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->weight('bold')
                    ->label('Nama Produk'),
                Tables\Columns\TextColumn::make('keterangan')
                    ->getStateUsing(fn (Product $record): string => 
                        $record->status_stok === 'BERLEBIH' 
                            ? "Sisa {$record->stok_aktual} {$record->satuan} (Maks: {$record->batas_maksimum})" 
                            : "Sisa {$record->stok_aktual} {$record->satuan} (Min: {$record->batas_minimum})"
                    )
                    ->color('gray')
                    ->label('Keterangan'),
                Tables\Columns\TextColumn::make('status_stok')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'BERLEBIH' => 'BERLEBIH',
                        'KRITIS' => 'KRITIS',
                        default => $state,
                    })
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'KRITIS' => 'warning',
                        'BERLEBIH' => 'danger',
                        default => 'gray',
                    })
                    ->label('Prioritas'),
            ])
            ->paginated(false);
    }
}
