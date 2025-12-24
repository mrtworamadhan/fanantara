<?php

namespace App\Filament\Resources\SavingTransactions;

use App\Filament\Resources\SavingTransactions\Pages\CreateSavingTransaction;
use App\Filament\Resources\SavingTransactions\Pages\EditSavingTransaction;
use App\Filament\Resources\SavingTransactions\Pages\ListSavingTransactions;
use App\Filament\Resources\SavingTransactions\Schemas\SavingTransactionForm;
use App\Filament\Resources\SavingTransactions\Tables\SavingTransactionsTable;
use App\Models\SavingTransaction;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SavingTransactionResource extends Resource
{
    protected static ?string $model = SavingTransaction::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    protected static string | UnitEnum | null $navigationGroup = 'Simpanan';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Teller';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return SavingTransactionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SavingTransactionsTable::configure($table);
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
            'index' => ListSavingTransactions::route('/'),
            'create' => CreateSavingTransaction::route('/create'),
            'edit' => EditSavingTransaction::route('/{record}/edit'),
        ];
    }
}
