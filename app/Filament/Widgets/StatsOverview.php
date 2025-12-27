<?php

namespace App\Filament\Widgets;

use App\Models\Member;
use App\Models\SavingAccount;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected ?string $pollingInterval = '15s';

    protected function getStats(): array
    {
        $totalMember = Member::count();
        $totalIndividu = Member::where('type', 'individual')->count();
        $totalInstitusi = Member::where('type', 'institution')->count();

        $modalTerkunci = SavingAccount::whereHas('savingType', function ($q) {
            $q->whereIn('code', ['SP', 'SW']);
        })->sum('balance');

        $danaLikuid = SavingAccount::whereHas('savingType', function ($q) {
            $q->where('code', 'SS');
        })->sum('balance');

        return [
            Stat::make('Total Anggota', number_format($totalMember))
                ->description("{$totalIndividu} Perorangan | {$totalInstitusi} Lembaga")
                ->descriptionIcon('heroicon-m-users')
                ->color('primary')
                ->chart([7, 3, 4, 5, 6, 3, 5, 3]),

            Stat::make('Modal Tertanam (SP+SW)', 'Rp ' . number_format($modalTerkunci, 0, ',', '.'))
                ->description('Ekuitas Jangka Panjang')
                ->descriptionIcon('heroicon-m-lock-closed')
                ->color('success')
                ->chart([10, 10, 10, 10, 10, 10, 10]),

            Stat::make('Dana Likuid (Sukarela)', 'Rp ' . number_format($danaLikuid, 0, ',', '.'))
                ->description('Siap Ditarik Anggota')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('warning') 
                ->chart([2, 10, 3, 12, 1, 15, 4]), 
        ];
    }
    protected function getPollingInterval(): ?string
    {
        return '15s';
    }
}