<?php

namespace App\Filament\Resources\JournalEntries\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class JournalEntriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('transaction_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('reference_number')
                    ->searchable(),
                TextColumn::make('description')
                    ->limit(30)
                    ->searchable(),
                TextColumn::make('total_amount')
                    ->money('IDR')
                    ->label('Nilai Transaksi'),
                TextColumn::make('sourceable_type')
                    ->label('Sumber')
                    ->formatStateUsing(function ($state) {
                        return class_basename($state); 
                    })
                    ->badge(),
            ])
            ->defaultSort('transaction_date', 'desc')
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
