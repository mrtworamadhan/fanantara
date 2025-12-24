<?php

namespace App\Filament\Resources\ShuDistributions\RelationManagers;

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

class DetailsRelationManager extends RelationManager
{
    protected static string $relationship = 'details';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('member.name')
                    ->required()
                    ->numeric(),
                TextInput::make('total_savings')
                    ->required()
                    ->numeric(),
                TextInput::make('total_purchases')
                    ->required()
                    ->numeric(),
                TextInput::make('shu_modal')
                    ->required()
                    ->numeric(),
                TextInput::make('shu_services')
                    ->required()
                    ->numeric(),
                TextInput::make('total_received')
                    ->required()
                    ->numeric(),
            ]);
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('member.name')
                    ->numeric(),
                TextEntry::make('total_savings')
                    ->numeric(),
                TextEntry::make('total_purchases')
                    ->numeric(),
                TextEntry::make('shu_modal')
                    ->numeric(),
                TextEntry::make('shu_services')
                    ->numeric(),
                TextEntry::make('total_received')
                    ->numeric(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('total_received')
            ->columns([
                TextColumn::make('member.name')
                    ->label('Anggota')
                    ->searchable(),
                
                TextColumn::make('total_savings')
                    ->label('Simpanan')
                    ->money('IDR'),
                    
                TextColumn::make('total_purchases')
                    ->label('Belanja')
                    ->money('IDR'),

                TextColumn::make('shu_modal')
                    ->label('Jasa Modal')
                    ->money('IDR')
                    ->color('info'),

                TextColumn::make('shu_services')
                    ->label('Jasa Transaksi')
                    ->money('IDR')
                    ->color('success'),

                TextColumn::make('total_received')
                    ->label('TOTAL SHU')
                    ->money('IDR')
                    ->weight('bold')
                    ->size('lg'),
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
            ])
            ->toolbarActions([
                // BulkActionGroup::make([
                //     DissociateBulkAction::make(),
                //     DeleteBulkAction::make(),
                // ]),
            ]);
    }
}
