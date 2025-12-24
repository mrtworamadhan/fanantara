<?php

namespace App\Filament\Resources\SiteSettings\Pages;

use App\Filament\Resources\SiteSettings\SiteSettingResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageSiteSettings extends ManageRecords
{
    protected static string $resource = SiteSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
    

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['value'] =
            $data['value_text']
            ?? $data['value_color']
            ?? $data['value_image']
            ?? null;

        unset(
            $data['value_text'],
            $data['value_color'],
            $data['value_image']
        );

        return $data;
    }


}
