<?php

namespace App\Filament\Resources\StockInTransactions;

use App\Filament\Resources\StockInTransactions\Pages\CreateStockInTransaction;
use App\Filament\Resources\StockInTransactions\Pages\EditStockInTransaction;
use App\Filament\Resources\StockInTransactions\Pages\ListStockInTransactions;
use App\Filament\Resources\StockInTransactions\Schemas\StockInTransactionForm;
use App\Filament\Resources\StockInTransactions\Tables\StockInTransactionsTable;
use App\Models\StockInTransaction;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class StockInTransactionResource extends Resource
{
    protected static ?string $model = StockInTransaction::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static bool $shouldRegisterNavigation = false;

    public static function form(Schema $schema): Schema
    {
        return StockInTransactionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StockInTransactionsTable::configure($table);
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
            'index' => ListStockInTransactions::route('/'),
            'create' => CreateStockInTransaction::route('/create'),
            'edit' => EditStockInTransaction::route('/{record}/edit'),
        ];
    }
}
