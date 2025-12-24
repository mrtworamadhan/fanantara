<?php

namespace App\Filament\Resources\OrganizationPositions;

use App\Filament\Resources\OrganizationPositions\Pages\ManageOrganizationPositions;
use App\Models\OrganizationPosition;
use BackedEnum;
use Filament\Schemas\Components\Grid;
use UnitEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class OrganizationPositionResource extends Resource
{
    protected static ?string $model = OrganizationPosition::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedStar;

    protected static string | UnitEnum | null $navigationGroup = 'Organisasi';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Daftar Jabatan';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(2)->schema([
                    TextInput::make('name')
                        ->label('Nama Jabatan')
                        ->placeholder('Contoh: Ketua Umum, Sekretaris')
                        ->required(),
                    
                    TextInput::make('level')
                        ->label('Level Hierarki')
                        ->numeric()
                        ->default(99)
                        ->helperText('Angka 1 adalah jabatan tertinggi.'),

                    Toggle::make('is_active')
                        ->label('Aktif?')
                        ->default(true),
                ])->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('level')
                    ->label('Hirarki Level')
                    ->sortable(),
                TextColumn::make('name')
                    ->searchable()
                    ->weight('bold'),
                IconColumn::make('is_active')
                    ->boolean(),
            ])
            ->defaultSort('level', 'asc')
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageOrganizationPositions::route('/'),
        ];
    }
}
