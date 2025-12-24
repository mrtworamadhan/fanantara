<?php

namespace App\Filament\Resources\SavingTypes;

use App\Filament\Resources\SavingTypes\Pages\ManageSavingTypes;
use App\Models\SavingType;
use BackedEnum;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Tables\Columns\BadgeColumn;
use UnitEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SavingTypeResource extends Resource
{
    protected static ?string $model = SavingType::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    protected static string | UnitEnum | null $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Pengaturan Simpanan';
    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Aturan Simpanan')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('name')
                                ->label('Nama Simpanan')
                                ->required()
                                ->maxLength(255),
                                
                            TextInput::make('code')
                                ->label('Kode Unik')
                                ->helperText('Contoh: SP, SW, SS')
                                ->required()
                                ->unique(ignoreRecord: true)
                                ->maxLength(5),

                            Select::make('category')
                                ->label('Kategori Akuntansi')
                                ->options([
                                    'equity' => 'EKUITAS (Modal) - Cth: Pokok/Wajib',
                                    'liability' => 'LIABILITAS (Hutang) - Cth: Sukarela/Tabungan',
                                ])
                                ->required(),

                            Toggle::make('is_withdrawable')
                                ->label('Bisa Ditarik?')
                                ->helperText('Aktifkan untuk jenis tabungan yang bisa diambil sewaktu-waktu.'),
                        ])
                        
                    ])->columnSpanFull(),

                Section::make('Nominal Default (Tarif)')
                    ->description('Isi 0 jika nominalnya bebas (tidak ditentukan).')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('amount_individual')
                                ->label('Tarif Perorangan (Rp)')
                                ->numeric()
                                ->prefix('Rp')
                                ->default(0),

                            TextInput::make('amount_institution')
                                ->label('Tarif Lembaga/Institusi (Rp)')
                                ->numeric()
                                ->prefix('Rp')
                                ->default(0),
                        ])
                        
                    ])->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('code')->badge()->color('gray'),
                TextColumn::make('name')->weight('bold')->searchable(),
                
                BadgeColumn::make('category')
                    ->colors([
                        'success' => 'equity',
                        'warning' => 'liability',
                    ])
                    ->formatStateUsing(fn (string $state): string => strtoupper($state)),

                TextColumn::make('amount_individual')
                    ->label('Tarif Orang')
                    ->money('IDR'),

                TextColumn::make('amount_institution')
                    ->label('Tarif Lembaga')
                    ->money('IDR'),
                    
                IconColumn::make('is_withdrawable')
                    ->label('Withdraw?')
                    ->boolean(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                // BulkActionGroup::make([
                //     DeleteBulkAction::make(),
                // ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageSavingTypes::route('/'),
        ];
    }
}
