<?php

namespace App\Filament\Resources\ShuDistributions;

use App\Filament\Resources\ShuDistributions\Pages\CreateShuDistribution;
use App\Filament\Resources\ShuDistributions\Pages\EditShuDistribution;
use App\Filament\Resources\ShuDistributions\Pages\ListShuDistributions;
use App\Filament\Resources\ShuDistributions\Pages\ViewShuDistribution;
use App\Filament\Resources\ShuDistributions\RelationManagers\DetailsRelationManager;
use App\Filament\Resources\ShuDistributions\Schemas\ShuDistributionForm;
use App\Filament\Resources\ShuDistributions\Tables\ShuDistributionsTable;
use App\Models\ShuDistribution;
use BackedEnum;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ShuDistributionResource extends Resource
{
    protected static ?string $model = ShuDistribution::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCurrencyDollar;

    protected static string | UnitEnum | null $navigationGroup = 'Keuangan';

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationLabel = 'Draft SHU';

    protected static ?string $recordTitleAttribute = 'name';

    public static function canEdit(Model $record): bool
    {
        return $record->status !== 'completed';
    }


    public static function form(Schema $schema): Schema
    {
        return ShuDistributionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ShuDistributionsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            DetailsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListShuDistributions::route('/'),
            'create' => CreateShuDistribution::route('/create'),
            'view' => ViewShuDistribution::route('/{record}'),
            'edit' => EditShuDistribution::route('/{record}/edit'),
        ];
    }
}
