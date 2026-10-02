<?php

namespace App\Filament\Resources\StockOutTransactions;

use App\Filament\Resources\StockOutTransactions\Pages\CreateStockOutTransaction;
use App\Filament\Resources\StockOutTransactions\Pages\EditStockOutTransaction;
use App\Filament\Resources\StockOutTransactions\Pages\ListStockOutTransactions;
use App\Filament\Resources\StockOutTransactions\Schemas\StockOutTransactionForm;
use App\Filament\Resources\StockOutTransactions\Tables\StockOutTransactionsTable;
use App\Models\StockOutTransaction;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class StockOutTransactionResource extends Resource
{
    protected static ?string $model = StockOutTransaction::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static bool $shouldRegisterNavigation = false;

    public static function form(Schema $schema): Schema
    {
        return StockOutTransactionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StockOutTransactionsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListStockOutTransactions::route('/'),
            'create' => CreateStockOutTransaction::route('/create'),
            'edit' => EditStockOutTransaction::route('/{record}/edit'),
        ];
    }
}
