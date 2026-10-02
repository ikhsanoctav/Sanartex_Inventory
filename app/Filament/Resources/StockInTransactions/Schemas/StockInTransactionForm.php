<?php

namespace App\Filament\Resources\StockInTransactions\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class StockInTransactionForm
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
                    ->numeric(),
                Textarea::make('keterangan')
                    ->columnSpanFull(),
            ]);
    }
}
