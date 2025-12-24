<?php

namespace App\Filament\Resources\OrganizationStructures\Pages;

use App\Filament\Resources\OrganizationStructures\OrganizationStructureResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageOrganizationStructures extends ManageRecords
{
    protected static string $resource = OrganizationStructureResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
