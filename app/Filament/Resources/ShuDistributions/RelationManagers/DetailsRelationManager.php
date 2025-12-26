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
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DetailsRelationManager extends RelationManager
{
    protected static string $relationship = 'details';

    // public function form(Schema $schema): Schema
    // {
    //     return $schema
    //         ->components([
    //             TextInput::make('member.name')
    //                 ->required()
    //                 ->numeric(),
    //             TextInput::make('total_savings')
    //                 ->required()
    //                 ->numeric(),
    //             TextInput::make('total_purchases')
    //                 ->required()
    //                 ->numeric(),
    //             TextInput::make('shu_modal')
    //                 ->required()
    //                 ->numeric(),
    //             TextInput::make('shu_services')
    //                 ->required()
    //                 ->numeric(),
    //             TextInput::make('total_received')
    //                 ->required()
    //                 ->numeric(),
    //         ]);
    // }

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Rincian Anggota')
                    ->schema([
                        TextEntry::make('member.name')->label('Nama Anggota'),
                        TextEntry::make('total_received')->label('Total SHU')->money('IDR')->weight('bold'),
                    ])->columns(2),
                
                Section::make('Breakdown Alokasi')
                    ->schema([
                        TextEntry::make('distribution_breakdown')
                            ->label('')
                            ->bulleted()
                            ->state(function ($record): array {
                                $data = collect($record->distribution_breakdown);

                                if ($data->isEmpty()) {
                                    return [];
                                }

                                return $data->map(fn ($val, $key) =>
                                    "{$key} : Rp " . number_format($val, 0, ',', '.')
                                )->toArray();
                            })
                            ->color('info'),
                    ])
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
                ->label('Saldo Simpanan')
                ->money('IDR'),
                
            TextColumn::make('total_purchases')
                ->label('Total Belanja')
                ->money('IDR'),

            // MEMBONGKAR JSON distribution_breakdown
            TextColumn::make('distribution_breakdown')
                ->label('Breakdown SHU')
                ->listWithLineBreaks()
                ->bulleted()
                ->state(function ($record): array {
                    $data = collect($record->distribution_breakdown);

                    if ($data->isEmpty()) {
                        return [];
                    }

                    return $data->map(fn ($val, $key) =>
                        "{$key} : Rp " . number_format($val, 0, ',', '.')
                    )->toArray();
                })
                ->wrap()
                ->color('info'),

            TextColumn::make('total_received')
                ->label('TOTAL DITERIMA')
                ->money('IDR')
                ->weight('bold')
                ->size('lg')
                ->color('success'),
        ])
        ->recordActions([
            ViewAction::make(),
        ]);
    }
}
