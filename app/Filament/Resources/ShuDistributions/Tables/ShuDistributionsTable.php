<?php

namespace App\Filament\Resources\ShuDistributions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ShuDistributionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('total_shu')
                    ->label('Total SHU')
                    ->color('success')
                    ->money('IDR'),

                TextColumn::make('allocation_results')
                    ->label('Rincian Alokasi')
                    ->listWithLineBreaks()
                    ->bulleted()
                    ->state(function ($record): array {
                        return collect($record->allocation_results)
                            ->map(fn ($item) =>
                                "{$item['name']} — {$item['percentage']}% | Rp " .
                                number_format($item['amount'], 0, ',', '.')
                            )
                            ->toArray();
                    })
                    ->wrap()
                    ->color('info'),
                

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'processed' => 'warning',
                        'completed' => 'success',
                        default => 'gray',
                    }),

                TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make()
                    ->visible(fn ($record) => $record->status !== 'completed'),
            ])

            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
