<?php

namespace App\Filament\Resources\StockOpnames\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class StockOpnamesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->date()
                    ->label('Tanggal'),
                TextColumn::make('opname_number')
                    ->searchable(),
                TextColumn::make('product.name')
                    ->weight('bold')
                    ->searchable(),
                
                TextColumn::make('system_qty')
                    ->label('Sistem')
                    ->alignCenter(),
                TextColumn::make('actual_qty')
                    ->label('Fisik')
                    ->alignCenter()
                    ->weight('bold'),
                
                TextColumn::make('difference')
                    ->label('Selisih')
                    ->badge()
                    ->color(fn (string $state): string => $state < 0 ? 'danger' : ($state > 0 ? 'success' : 'gray')),
                
                TextColumn::make('user.name')
                    ->label('Admin'),
            ])
            ->defaultSort('created_at', 'desc')
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
