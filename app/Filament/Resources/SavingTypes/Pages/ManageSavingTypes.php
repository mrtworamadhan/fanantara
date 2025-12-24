<?php

namespace App\Filament\Resources\SavingTypes\Pages;

use App\Filament\Resources\SavingTypes\SavingTypeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageSavingTypes extends ManageRecords
{
    protected static string $resource = SavingTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
