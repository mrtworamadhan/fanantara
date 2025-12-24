<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sku')
                    ->searchable()
                    ->sortable(),
                
                ImageColumn::make('image')
                    ->label('Foto')
                    ->square()
                    ->disk('public')
                    ->visibility('public'),
                    
                TextColumn::make('name')
                    ->searchable()
                    ->weight('bold'),
                    
                TextColumn::make('supplier.name')
                    ->label('Supplier')
                    ->getStateUsing(fn ($record) => $record->supplier->name ?? '-'),

                TextColumn::make('base_price')
                    ->label('HPP')
                    ->money('IDR')
                    ->sortable(),
                
                TextColumn::make('inventory_stocks_sum_quantity')
                    ->label('Stok Gudang')
                    ->sum('inventoryStocks', 'quantity') 
                    ->badge()
                    ->color(fn ($state) => $state <= 5 ? 'danger' : 'success')
                    ->sortable(),
                    
                IconColumn::make('is_active')
                    ->boolean(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
