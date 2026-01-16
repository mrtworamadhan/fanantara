<?php

namespace App\Filament\Resources\RegistrationFees;

use App\Filament\Resources\RegistrationFees\Pages\ManageRegistrationFees;
use App\Models\RegistrationFee;
use BackedEnum;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;
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

class RegistrationFeeResource extends Resource
{
    protected static ?string $model = RegistrationFee::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedReceiptRefund;

    protected static string | UnitEnum | null $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Biaya Pendaftaran';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Detail Komponen Biaya')
                    ->description('Tentukan nama biaya dan target anggota.')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Biaya')
                            ->placeholder('Contoh: Simpanan Pokok')
                            ->required()
                            ->maxLength(255),

                        Select::make('member_type')
                            ->label('Target Tipe Anggota')
                            ->options([
                                'individual' => 'Individu',
                                'institution' => 'Institusi / Lembaga',
                                'all' => 'Semua Tipe',
                            ])
                            ->required()
                            ->native(false),

                        TextInput::make('amount')
                            ->label('Nominal Biaya')
                            ->numeric()
                            ->prefix('Rp')
                            ->required(),

                        Toggle::make('is_active')
                            ->label('Status Aktif')
                            ->default(true)
                            ->helperText('Hanya biaya aktif yang akan muncul di tagihan pendaftar.'),
                    ])->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Komponen')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('member_type')
                    ->label('Target')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'individual' => 'info',
                        'institution' => 'warning',
                        'all' => 'success',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'individual' => 'Individu',
                        'institution' => 'Institusi',
                        'all' => 'Semua',
                    }),

                TextColumn::make('amount')
                    ->label('Nominal')
                    ->money('IDR')
                    ->sortable(),

                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('member_type')
                    ->options([
                        'individual' => 'Individu',
                        'institution' => 'Institusi',
                        'all' => 'Semua',
                    ]),
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
            'index' => ManageRegistrationFees::route('/'),
        ];
    }
}
