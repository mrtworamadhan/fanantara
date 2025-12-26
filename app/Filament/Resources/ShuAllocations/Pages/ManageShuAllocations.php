<?php

namespace App\Filament\Resources\ShuAllocations\Pages;

use App\Filament\Resources\ShuAllocations\ShuAllocationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageShuAllocations extends ManageRecords
{
    protected static string $resource = ShuAllocationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
