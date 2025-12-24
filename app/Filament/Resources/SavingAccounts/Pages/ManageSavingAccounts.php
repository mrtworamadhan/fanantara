<?php

namespace App\Filament\Resources\SavingAccounts\Pages;

use App\Filament\Resources\SavingAccounts\SavingAccountResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ManageSavingAccounts extends ManageRecords
{
    protected static string $resource = SavingAccountResource::class;

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Semua Rekening')
                ->icon('heroicon-m-list-bullet'),

            'sp' => Tab::make('Simpanan Pokok')
                ->icon('heroicon-m-lock-closed')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereHas('savingType', function ($q) {
                    $q->where('code', 'SP')->orWhere('name', 'like', '%Pokok%');
                }))
                ->badge(\App\Models\SavingAccount::whereHas('savingType', fn ($q) => $q->where('code', 'SP')->orWhere('name', 'like', '%Pokok%'))->count())
                ->badgeColor('danger'),

            'sw' => Tab::make('Simpanan Wajib')
                ->icon('heroicon-m-calendar')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereHas('savingType', function ($q) {
                    $q->where('code', 'SW')->orWhere('name', 'like', '%Wajib%');
                }))
                ->badge(\App\Models\SavingAccount::whereHas('savingType', fn ($q) => $q->where('code', 'SW')->orWhere('name', 'like', '%Wajib%'))->count())
                ->badgeColor('warning'),

            'ss' => Tab::make('Simpanan Sukarela')
                ->icon('heroicon-m-banknotes')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereHas('savingType', function ($q) {
                    $q->where('code', 'SS')->orWhere('name', 'like', '%Sukarela%');
                }))
                ->badge(\App\Models\SavingAccount::whereHas('savingType', fn ($q) => $q->where('code', 'SS')->orWhere('name', 'like', '%Sukarela%'))->count())
                ->badgeColor('success'),
        ];
    }
}
