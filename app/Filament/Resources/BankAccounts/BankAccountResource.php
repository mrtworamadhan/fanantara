<?php

namespace App\Filament\Resources\BankAccounts;

use App\Filament\Resources\BankAccounts\Pages\ManageBankAccounts;
use App\Models\BankAccount;
use BackedEnum;
use UnitEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class BankAccountResource extends Resource
{
    protected static ?string $model = BankAccount::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCreditCard;

    protected static string | UnitEnum | null $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationLabel = 'Rekening BANK';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Info Rekening')
                    ->columnSpanFull()
                    ->schema([
                        FileUpload::make('logo')
                            ->label('Logo Bank')
                            ->image()
                            ->disk('public')
                            ->visibility('public')
                            ->directory('bank-logos')
                            ->columnSpanFull(),

                        Grid::make(2)
                            ->schema([
                                TextInput::make('bank_name')
                                    ->label('Nama Bank')
                                    ->placeholder('BCA / Mandiri')
                                    ->required(),

                                TextInput::make('account_number')
                                    ->label('Nomor Rekening')
                                    ->required(),
                            ]),

                        TextInput::make('account_holder')
                            ->label('Atas Nama')
                            ->placeholder('KOPERASI FANANTARA')
                            ->required()
                            ->columnSpanFull(),
                        
                        Toggle::make('is_active')
                            ->label('Aktif / Gunakan')
                            ->default(true),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                ImageColumn::make('logo')
                    ->disk('public')
                    ->height(40),
                
                TextColumn::make('bank_name')
                    ->weight('bold')
                    ->searchable(),

                TextColumn::make('account_number')
                    ->icon('heroicon-m-clipboard')
                    ->copyable()
                    ->searchable(),

                TextColumn::make('account_holder'),

                ToggleColumn::make('is_active')
                    ->label('Aktif'),
            ])
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
            'index' => ManageBankAccounts::route('/'),
        ];
    }
}
