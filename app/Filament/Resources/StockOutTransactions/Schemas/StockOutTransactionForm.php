<?php

namespace App\Filament\Resources\StockOutTransactions\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class StockOutTransactionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('product_id')
                    ->relationship('product', 'nama')
                    ->required(),
                DatePicker::make('tanggal')
                    ->required(),
                TextInput::make('jumlah')
                    ->required()
                    ->numeric()
                    ->rule(function ($get) {
                        return function (string $attribute, $value, \Closure $fail) use ($get) {
                            $product = \App\Models\Product::find($get('product_id'));
                            if ($product && $value > $product->stok_aktual) {
                                $fail("Jumlah melebihi stok yang tersedia ({$product->stok_aktual}).");
                            }
                        };
                    }),
                Textarea::make('keterangan')
                    ->columnSpanFull(),
            ]);
    }
}
