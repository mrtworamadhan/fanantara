<?php

namespace App\Filament\Resources\ShuDistributions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ShuDistributionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('accounting_period_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('total_shu')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('percentage_modal')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('percentage_services')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('percentage_reserves')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('amount_modal')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('amount_services')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('status')
                    ->searchable(),
                TextColumn::make('created_by')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
