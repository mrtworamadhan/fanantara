<?php

namespace App\Filament\Resources\ShuAllocations;

use App\Filament\Resources\ShuAllocations\Pages\ManageShuAllocations;
use App\Models\ShuAllocation;
use BackedEnum;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\TernaryFilter;
use UnitEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ShuAllocationResource extends Resource
{
    protected static ?string $model = ShuAllocation::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartPie;
    protected static string | UnitEnum | null $navigationGroup = 'Organisasi';

    protected static ?int $navigationSort = 3;
    protected static ?string $navigationLabel = 'Alokasi SHU';
    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Detail Alokasi')
                    ->description('Tentukan persentase pembagian Sisa Hasil Usaha.')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('name')
                                ->label('Nama Alokasi')
                                ->required()
                                ->placeholder('Contoh: Jasa Modal')
                                ->maxLength(255),

                            TextInput::make('code')
                                ->label('Kode Alokasi')
                                ->required()
                                ->unique(ignoreRecord: true)
                                ->placeholder('Contoh: JM')
                                ->maxLength(10),

                            Select::make('account_id')
                                ->label('Akun Tujuan Jurnal')
                                ->relationship('account', 'name')
                                ->searchable()
                                ->required(),

                            TextInput::make('percentage')
                                ->label('Persentase (%)')
                                ->numeric()
                                ->required()
                                ->minValue(0)
                                ->maxValue(100)
                                ->suffix('%')
                                ->hint('Total seluruh alokasi harus 100%'),

                            Toggle::make('is_active')
                                ->label('Status Aktif')
                                ->default(true)
                                ->required(),

                            Textarea::make('description')
                                ->label('Keterangan')
                                ->columnSpanFull(),
                        ])
                        
                    ])->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Alokasi')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('code')
                    ->label('Kode')
                    ->badge()
                    ->color('gray'),

                TextColumn::make('percentage')
                    ->label('Persentase')
                    ->numeric(decimalPlaces: 2)
                    ->suffix('%')
                    ->sortable()
                    ->alignment('center'),

                IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('updated_at')
                    ->label('Terakhir Diubah')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Hanya yang Aktif'),
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
            'index' => ManageShuAllocations::route('/'),
        ];
    }
}
