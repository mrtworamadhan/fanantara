<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->weight('bold'),
                
                TextColumn::make('email')
                    ->icon('heroicon-m-envelope')
                    ->searchable(),

                TextColumn::make('roles.name')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'super_admin' => 'danger',
                        'cashier' => 'success',
                        'warehouse' => 'warning',
                        'finance' => 'info',
                        default => 'gray',
                    })
                    ->sortable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('role')
                    ->relationship('roles', 'name'),
            ])
            ->recordActions([
                EditAction::make()->label(''),
                DeleteAction::make()->label('')
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
