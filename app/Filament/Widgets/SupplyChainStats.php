<?php

namespace App\Filament\Widgets;

use App\Models\IndividualProfile;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SupplyChainStats extends BaseWidget
{
    protected static ?int $sort = 2;

    protected function getStats(): array
    {
        $profiles = IndividualProfile::select('production_profile', 'consumption_profile')->get();

        $totalLahan = $profiles->sum(function ($profile) {
            return (float) ($profile->production_profile['luas_lahan'] ?? 0);
        });

        $totalPanen = $profiles->sum(function ($profile) {
            return (float) ($profile->production_profile['estimasi_panen'] ?? 0);
        });

        $butuhPupuk = $profiles->sum(function ($profile) {
            return (float) ($profile->consumption_profile['pupuk'] ?? 0);
        });

        return [
            Stat::make('Total Luas Lahan', number_format($totalLahan, 1) . ' Ha')
                ->description('Basis Produksi Anggota')
                ->icon('heroicon-m-map')
                ->color('info'),

            Stat::make('Estimasi Panen Raya', number_format($totalPanen, 0) . ' Ton')
                ->description('Potensi Offtaker')
                ->icon('heroicon-m-truck')
                ->color('success'),

            Stat::make('Demand Pupuk', number_format($butuhPupuk, 0) . ' Kg')
                ->description('Potensi Penjualan Koperasi')
                ->icon('heroicon-m-shopping-cart')
                ->color('danger'),
        ];
    }
    protected function getPollingInterval(): ?string
    {
        return '15s';
    }
}