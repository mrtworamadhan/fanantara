<?php

namespace App\Filament\Resources\Posts\Pages;

use App\Filament\Resources\Posts\PostResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListPosts extends ListRecords
{
    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All')
                ->icon('heroicon-m-list-bullet'),

            'news' => Tab::make('Berita')
                ->icon('heroicon-m-newspaper') // Saya sarankan icon gedung biar relevan
                ->modifyQueryUsing(fn (Builder $query) => $query->where('category', 'news'))
                ->badge(\App\Models\Post::where('category', 'news')
                    ->count())
                ->badgeColor('warning'),
            
            'activity' => Tab::make('Kegiatan')
                ->icon('heroicon-m-paper-clip')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('category', 'activity'))
                ->badge(\App\Models\Post::where('category', 'activity')
                    ->count())
                ->badgeColor('success'),
            'announcement' => Tab::make('Pengumuman')
                ->icon('heroicon-m-megaphone')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('category', 'announcement'))
                ->badge(\App\Models\Post::where('category', 'announcement')
                    ->count())
                ->badgeColor('success'),
        ];
    }
}
