<?php

namespace App\Filament\Resources\StockOpnames\Schemas;

use App\Models\InventoryStock;
use App\Models\Product;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class StockOpnameForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Input Opname')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('opname_number')
                                ->label('No. Opname')
                                ->default('OP-' . date('YmdHis'))
                                ->readOnly()
                                ->required(),

                            Select::make('product_id')
                                ->label('Produk')
                                ->options(Product::all()->pluck('name', 'id'))
                                ->searchable()
                                ->required()
                                ->live() 
                                ->afterStateUpdated(function ($state, Set $set) {
                                    $stock = InventoryStock::where('product_id', $state)
                                        ->where('warehouse_id', 1) 
                                        ->first();
                                    
                                    $qty = $stock ? $stock->quantity : 0;
                                    $set('system_qty', $qty);
                                    $set('difference', 0); 
                                }),

                            TextInput::make('system_qty')
                                ->label('Stok Sistem')
                                ->numeric()
                                ->readOnly() 
                                ->required()
                                ->dehydrated(), 

                            TextInput::make('actual_qty')
                                ->label('Stok Fisik (Actual)')
                                ->numeric()
                                ->required()
                                ->live(onBlur: true)
                                ->afterStateUpdated(function ($state, Get $get, Set $set) {
                                    $system = $get('system_qty') ?? 0;
                                    $diff = (int)$state - (int)$system;
                                    $set('difference', $diff);
                                }),

                            TextInput::make('difference')
                                ->label('Selisih')
                                ->numeric()
                                ->readOnly()
                                ->dehydrated()
                                ->suffix(' Pcs')
                                ->helperText('Minus (-) berarti barang hilang. Plus (+) berarti barang lebih.'),

                            Textarea::make('notes')
                                ->label('Keterangan / Alasan')
                                ->placeholder('Contoh: Barang rusak kena air, atau salah hitung sebelumnya.')
                                ->columnSpanFull(),
                                
                            Hidden::make('user_id')
                                ->default(auth()->id()),
                        ])
                        
                    ])->columnSpanFull()
            ]);
    }
}
