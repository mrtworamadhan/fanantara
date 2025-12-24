<?php

namespace App\Filament\Resources\Members\RelationManagers;

use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SavingAccountsRelationManager extends RelationManager
{
    protected static string $relationship = 'savingAccounts';

    protected static ?string $title = 'Rekening Simpanan';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('account_number')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('savingtype.name')
                    ->label('Jenis Simpanan')
                    ->sortable()
                    ->badge()
                    ->colors([
                        'primary' => 'Simpanan Pokok',
                        'warning' => 'Simpanan Wajib',
                        'success' => 'Simpanan Sukarela',
                    ]),
                
                TextColumn::make('account_number')
                    ->label('No. Rekening')
                    ->copyable(),

                TextColumn::make('balance')
                    ->label('Saldo Saat Ini')
                    ->money('IDR')
                    ->weight('bold')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // CreateAction::make(),
                // AssociateAction::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                // EditAction::make(),
                // DissociateAction::make(),
                // DeleteAction::make(),
            ])
            ->toolbarActions([
                // BulkActionGroup::make([
                //     DissociateBulkAction::make(),
                //     DeleteBulkAction::make(),
                // ]),
            ]);
    }
}
