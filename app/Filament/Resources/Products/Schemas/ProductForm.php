<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                TextInput::make('nama')
                    ->required(),
                \Filament\Forms\Components\Hidden::make('kategori')
                    ->default('Pakaian'),
                TextInput::make('satuan')
                    ->required(),
                TextInput::make('stok_aktual')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('batas_minimum')
                    ->required()
                    ->numeric(),
                TextInput::make('batas_maksimum')
                    ->required()
                    ->numeric(),
                \Filament\Forms\Components\Hidden::make('status_stok')
                    ->default('NORMAL'),
            ]);
    }
}
