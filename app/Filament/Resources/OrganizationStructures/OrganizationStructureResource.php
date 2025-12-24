<?php

namespace App\Filament\Resources\OrganizationStructures;

use App\Filament\Resources\OrganizationStructures\Pages\ManageOrganizationStructures;
use App\Models\OrganizationStructure;
use BackedEnum;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;
use Filament\Tables\Filters\SelectFilter;
use UnitEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class OrganizationStructureResource extends Resource
{
    protected static ?string $model = OrganizationStructure::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static string | UnitEnum | null $navigationGroup = 'Organisasi';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Struktur Pengurus';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(2)->schema([
                    Select::make('member_id')
                        ->label('Nama Pengurus')
                        ->searchable()
                        ->getSearchResultsUsing(function (string $search) {
                            
                            return \App\Models\Member::query()
                                ->where('type', 'individual') 
                                
                                ->whereHasMorph(
                                    'profileable',
                                    [\App\Models\IndividualProfile::class], 
                                    function ($query) use ($search) {
                                        $query->where('full_name', 'like', "%{$search}%");
                                    }
                                )
                                ->limit(50)
                                ->get()
                                ->mapWithKeys(function ($member) {
                                    return [$member->id => $member->profileable->full_name . " (ID: {$member->member_number})"];
                                });
                        })
                        ->getOptionLabelUsing(function ($value): ?string {
                            $member = \App\Models\Member::find($value);
                            return $member ? $member->profileable->full_name : null;
                        })
                        ->required(),

                    Select::make('organization_position_id')
                        ->relationship('position', 'name')
                        ->label('Menjabat Sebagai')
                        ->required(),

                    DatePicker::make('start_date')
                        ->label('Mulai Menjabat')
                        ->required()
                        ->default(now()),

                    DatePicker::make('end_date')
                        ->label('Selesai Menjabat')
                        ->helperText('Kosongkan jika masih menjabat.'),

                    Toggle::make('is_active')
                        ->label('Status Aktif')
                        ->default(true),
                ])->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('member.name')
                    ->label('Nama Pengurus')
                    ->searchable()
                    ->weight('bold'),

                TextColumn::make('position.name')
                    ->label('Jabatan')
                    ->badge()
                    ->color('info'),

                TextColumn::make('start_date')->date(),
                
                TextColumn::make('end_date')
                    ->date()
                    ->placeholder('Sekarang'),

                IconColumn::make('is_active')->boolean(),
            ])
            ->filters([
                SelectFilter::make('organization_position_id')
                    ->relationship('position', 'name')
                    ->label('Filter Jabatan'),
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
            'index' => ManageOrganizationStructures::route('/'),
        ];
    }
}
