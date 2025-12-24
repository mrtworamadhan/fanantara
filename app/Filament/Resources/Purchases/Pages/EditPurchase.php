<?php

namespace App\Filament\Resources\Purchases\Pages;

use App\Filament\Resources\Purchases\PurchaseResource;
use App\Models\InventoryStock;
use App\Models\StockMovement;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\DB;

class EditPurchase extends EditRecord
{
    protected static string $resource = PurchaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('receive_goods')
                ->label('Terima Barang (Masuk Stok)')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Konfirmasi Penerimaan Barang')
                ->modalDescription('Apakah Anda yakin barang sudah diterima fisik? Stok akan bertambah otomatis dan PO akan dikunci.')
                ->visible(fn ($record) => $record->status !== 'received' && $record->status !== 'cancelled')
                ->action(function ($record) {
                    DB::transaction(function () use ($record) {
                        
                        foreach ($record->items as $item) {
                            
                            $stock = InventoryStock::firstOrCreate(
                                [
                                    'warehouse_id' => $record->warehouse_id,
                                    'product_id'   => $item->product_id,
                                ],
                                [
                                    'quantity' => 0, 
                                    'min_stock_alert' => 10
                                ]
                            );

                            $stock->increment('quantity', $item->quantity);

                            StockMovement::create([
                                'inventory_stock_id' => $stock->id,
                                'user_id'            => auth()->id(),
                                'type'               => 'in',
                                'quantity'           => $item->quantity,
                                'reference_number'   => $record->purchase_number,
                                'notes'              => 'Penerimaan PO dari ' . $record->supplier->name,
                            ]);
                        }

                        $record->update(['status' => 'received']);
                    });

                    Notification::make()
                        ->title('Stok Berhasil Ditambahkan')
                        ->success()
                        ->send();
                }),

            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if ($this->record->status === 'received') {
            Notification::make()
                ->title('Error')
                ->body('PO yang sudah diterima tidak bisa diedit lagi!')
                ->danger()
                ->send();
            
            $this->halt();
        }
        
        return $data;
    }
}
