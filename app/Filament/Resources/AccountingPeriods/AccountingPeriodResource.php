<?php

namespace App\Filament\Resources\AccountingPeriods;

use App\Filament\Resources\AccountingPeriods\Pages\ManageAccountingPeriods;
use App\Models\AccountingPeriod;
use BackedEnum;
use UnitEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AccountingPeriodResource extends Resource
{
    protected static ?string $model = AccountingPeriod::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendar;

    protected static string | UnitEnum | null $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Periode Keuangan';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Periode Buku')
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Periode')
                            ->placeholder('Contoh: Tahun Buku 2024')
                            ->required()
                            ->maxLength(255),
                        
                        Grid::make(2)->schema([
                            DatePicker::make('start_date')
                                ->label('Tanggal Mulai')
                                ->required(),
                            DatePicker::make('end_date')
                                ->label('Tanggal Selesai')
                                ->required(),
                        ]),

                        Toggle::make('is_closed')
                            ->label('Tutup Buku (Closed)')
                            ->helperText('Jika aktif, jurnal tidak bisa ditambahkan ke periode ini.')
                            ->default(false),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->sortable()
                    ->searchable()
                    ->weight('bold'),
                    
                TextColumn::make('start_date')
                    ->label('Start')
                    ->date(),
                TextColumn::make('end_date')
                    ->label('End')
                    ->date(),
                
                IconColumn::make('is_closed')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-lock-closed')
                    ->falseIcon('heroicon-o-lock-open')
                    ->trueColor('danger')
                    ->falseColor('success'),
            ])
            ->defaultSort('start_date', 'desc')
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
            'index' => ManageAccountingPeriods::route('/'),
        ];
    }
}
