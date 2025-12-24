<?php

namespace App\Filament\Resources\SavingTransactions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SavingTransactionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('transaction_date')->date()->sortable(),
                TextColumn::make('account.member.name')->label('Anggota')->searchable(), // Relasi jauh
                TextColumn::make('account.type.name')->label('Jenis')->sortable(),
                BadgeColumn::make('type')
                    ->colors([
                        'success' => 'deposit',
                        'danger' => 'withdrawal',
                    ]),
                TextColumn::make('amount')->money('IDR'),
                TextColumn::make('reference_number')->label('No Ref'),
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
