<?php

namespace App\Filament\Resources\Accounts\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AccountsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->label('Kode')
                    ->sortable()
                    ->searchable(),
                    
                TextColumn::make('name')
                    ->label('Nama Akun')
                    ->searchable(),
                    
                BadgeColumn::make('type')
                    ->label('Kategori')
                    ->colors([
                        'primary' => 'asset',
                        'danger' => 'liability',
                        'success' => 'equity',
                        'warning' => 'revenue',
                        'gray' => 'expense',
                    ])
                    ->sortable(),
                    
                IconColumn::make('is_active')
                    ->boolean(),
            ])
            ->defaultSort('code')
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
