<?php

namespace App\Filament\Resources\RegistrationFees\Pages;

use App\Filament\Resources\RegistrationFees\RegistrationFeeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageRegistrationFees extends ManageRecords
{
    protected static string $resource = RegistrationFeeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
