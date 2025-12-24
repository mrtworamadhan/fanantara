<?php

namespace App\Filament\Resources\ShuDistributions\Schemas;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class ShuDistributionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                
                Section::make('Parameter Pembagian SHU')
                    ->schema([
                        Select::make('accounting_period_id')
                            ->relationship('period', 'name')
                            ->label('Tahun Buku')
                            ->required(),

                        TextInput::make('total_shu')
                            ->label('Total SHU (Laba Bersih)')
                            ->numeric()
                            ->prefix('Rp')
                            ->helperText('Masukkan nominal Laba Bersih setelah pajak (bisa lihat di Laporan Laba Rugi).')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, Get $get, Set $set) {
                                self::calculateAmounts($state, $get, $set);
                            }),
                        Hidden::make('created_by')
                            ->default(fn () => auth()->id()),
                    ]),

                Section::make('Alokasi Persentase (%)')
                    ->description('Tentukan porsi pembagian sesuai hasil RAT.')
                    ->schema([
                        Grid::make(3)->schema([
                            TextInput::make('percentage_modal')
                                ->label('% Jasa Modal')
                                ->numeric()
                                ->suffix('%')
                                ->default(40)
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn ($state, $get, $set) => self::calculateAmounts($get('total_shu'), $get, $set)),

                            TextInput::make('percentage_services')
                                ->label('% Jasa Usaha')
                                ->numeric()
                                ->suffix('%')
                                ->default(30)
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn ($state, $get, $set) => self::calculateAmounts($get('total_shu'), $get, $set)),

                            TextInput::make('percentage_reserves')
                                ->label('% Cadangan/Lainnya')
                                ->numeric()
                                ->suffix('%')
                                ->default(30)
                                ->readOnly() // Sisanya otomatis
                                ->dehydrated(), 
                        ]),

                        Grid::make(2)->schema([
                            TextInput::make('amount_modal')
                                ->label('Total Dana Jasa Modal')
                                ->prefix('Rp')
                                ->readOnly(),
                            
                            TextInput::make('amount_services')
                                ->label('Total Dana Jasa Usaha')
                                ->prefix('Rp')
                                ->readOnly(),
                        ]),
                    ]),
            ]);
    }

    public static function calculateAmounts($totalShu, $get, $set)
    {
        $totalShu = (float) $totalShu;
        $pModal = (int) $get('percentage_modal');
        $pServices = (int) $get('percentage_services');
        
        // Hitung sisa untuk cadangan
        $pReserves = 100 - ($pModal + $pServices);
        $set('percentage_reserves', max(0, $pReserves));

        // Hitung Nominal Rupiah
        $set('amount_modal', $totalShu * ($pModal / 100));
        $set('amount_services', $totalShu * ($pServices / 100));
    }
}
