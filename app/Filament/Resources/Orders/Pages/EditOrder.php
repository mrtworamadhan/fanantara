<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use App\Models\InventoryStock;
use App\Models\StockMovement;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\DB;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('process_order')
                ->label('Kirim Barang (Potong Stok)')
                ->icon('heroicon-o-truck')
                ->color('warning')
                ->requiresConfirmation()
                ->modalDescription('Stok gudang akan dikurangi. Pastikan pembayaran sudah LUNAS.')
                ->visible(fn ($record) => $record->status !== 'completed' && $record->status !== 'cancelled')
                ->action(function ($record) {
                    
                    DB::transaction(function () use ($record) {
                        
                        foreach ($record->items as $item) {
                            $stock = InventoryStock::where('warehouse_id', $record->warehouse_id)
                                ->where('product_id', $item->product_id)
                                ->first();

                            if (!$stock || $stock->quantity < $item->quantity) {
                                throw new \Exception("Stok Produk {$item->product->name} tidak cukup! Sisa: " . ($stock->quantity ?? 0));
                            }

                            $stock->decrement('quantity', $item->quantity);

                            StockMovement::create([
                                'inventory_stock_id' => $stock->id,
                                'user_id'            => auth()->id(),
                                'type'               => 'out',
                                'quantity'           => $item->quantity,
                                'reference_number'   => $record->order_number,
                                'notes'              => 'Penjualan ke Member: ' . $record->member->name,
                            ]);
                        }

                        $record->update([
                            'status' => 'completed',
                            // 'payment_status' => 'paid'
                        ]);
                    });

                    Notification::make()
                        ->title('Order Berhasil! Stok Terpotong.')
                        ->success()
                        ->send();
                })
                ->after(function () {
                   // Filament handle exception auto alert usually, but logic above handles standard flow
                }),

            DeleteAction::make(),
        ];
    }
}
