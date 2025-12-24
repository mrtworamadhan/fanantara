<?php

namespace App\Filament\Resources\AccountingPeriods\Pages;

use App\Filament\Resources\AccountingPeriods\AccountingPeriodResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageAccountingPeriods extends ManageRecords
{
    protected static string $resource = AccountingPeriodResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
