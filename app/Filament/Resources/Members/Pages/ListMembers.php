<?php

namespace App\Filament\Resources\Members\Pages;

use App\Filament\Resources\Members\MemberResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListMembers extends ListRecords
{
    protected static string $resource = MemberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Semua Data')
                ->icon('heroicon-m-list-bullet'),

            'institutions' => Tab::make('Koperasi / Lembaga')
                ->icon('heroicon-m-building-library') // Saya sarankan icon gedung biar relevan
                ->modifyQueryUsing(fn (Builder $query) => $query->where('type', 'institution'))
                ->badge(\App\Models\Member::where('type', 'institution')->count())
                ->badgeColor('warning'),
            
            'individuals' => Tab::make('Perorangan')
                ->icon('heroicon-m-user-group')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('type', 'individual'))
                ->badge(\App\Models\Member::where('type', 'individual')->count())
                ->badgeColor('success'), // Warna badge hijau
        ];
    }
}
