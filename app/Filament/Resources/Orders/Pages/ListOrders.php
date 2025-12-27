<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use App\Models\Order;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
    public function getTabs(): array
    {
        return [

            'pending' => Tab::make('Pending')
                ->icon('heroicon-m-shopping-cart')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'pending'))
                ->badge(
                    Order::where('status', 'pending')->count()
                )
                ->badgeColor('warning'),

            'processing' => Tab::make('Perlu di Proses')
                ->icon('heroicon-m-paper-airplane')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'processing'))

                ->badge(
                    Order::where('status', 'processing')->count())
                ->badgeColor('danger'),
            
            'completed' => Tab::make('Selesai')
                ->icon('heroicon-m-check-badge')
                ->modifyQueryUsing(fn (Builder $query) => 
                    $query->where('status', 'completed')
                )
                ->badge(
                    Order::where('status', 'completed')->count())
                ->badgeColor('success'),

            'cencelled' => Tab::make('Dibatalkan')
                ->icon('heroicon-m-x-circle')
                ->modifyQueryUsing(fn (Builder $query) => 
                    $query->where('status', 'cencelled')
                )
                ->badge(
                    Order::where('status', 'cencelled')->count())
                ->badgeColor('danger'),
            
            'all' => Tab::make('Semua')
                ->icon('heroicon-m-list-bullet'),

        ];
    }
    protected function getHeaderWidgets(): array
    {
        return [];
    }
    
    public function getTitle(): string 
    {
        return 'Orders';
    }

    protected function getPollingInterval(): ?string
    {
        return '15s';
    }
}
