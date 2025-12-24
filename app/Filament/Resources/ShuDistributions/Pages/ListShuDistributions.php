<?php

namespace App\Filament\Resources\ShuDistributions\Pages;

use App\Filament\Resources\ShuDistributions\ShuDistributionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListShuDistributions extends ListRecords
{
    protected static string $resource = ShuDistributionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
