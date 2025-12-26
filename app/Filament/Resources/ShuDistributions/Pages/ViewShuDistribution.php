<?php

namespace App\Filament\Resources\ShuDistributions\Pages;

use App\Filament\Resources\ShuDistributions\RelationManagers\DetailsRelationManager;
use App\Filament\Resources\ShuDistributions\ShuDistributionResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewShuDistribution extends ViewRecord
{
    protected static string $resource = ShuDistributionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // EditAction::make(),
        ];
    }
    public static function getRelations(): array
    {
        return [
            DetailsRelationManager::class,
        ];
    }
}
