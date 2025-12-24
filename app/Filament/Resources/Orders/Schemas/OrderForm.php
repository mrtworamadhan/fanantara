<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make()
                    ->schema([
                        Section::make('Info Order')
                            ->schema([
                                TextInput::make('order_number')
                                    ->default('ORD-' . date('YmdHis'))
                                    ->readOnly(),
                                Select::make('member_id')
                                    ->label('Pembeli (Member)')
                                    ->options(\App\Models\Member::get()->pluck('name', 'id')) // Pakai Accessor Name
                                    ->searchable()
                                    ->required(),
                                Select::make('warehouse_id')
                                    ->label('Gudang Asal')
                                    ->relationship('warehouse', 'name')
                                    ->default(1) // Default Gudang Pusat (sesuaikan ID)
                                    ->required(),
                                Select::make('status')
                                    ->options([
                                        'pending' => 'Pending',
                                        'processing' => 'Processing (Dikemas)',
                                        'completed' => 'Completed (Dikirim/Selesai)',
                                        'cancelled' => 'Cancelled',
                                    ])
                                    ->default('pending')
                                    ->required()
                                    ->disableOptionWhen(fn ($value) => $value === 'completed'),
                                Select::make('payment_status')
                                    ->options([
                                        'unpaid' => 'Belum Bayar',
                                        'paid' => 'Lunas',
                                    ])
                                    ->default('unpaid')
                                    ->required(),
                            ])->columns(2),
                    ])->columnSpan(2),

                // --- REKAP TOTAL ---
                Group::make()
                    ->schema([
                        Section::make('Total Tagihan')
                            ->schema([
                                TextInput::make('total_amount')
                                    ->prefix('Rp')
                                    ->numeric()
                                    ->readOnly()
                                    ->default(0),
                            ]),
                    ])->columnSpan(1),

                // --- ITEM KERANJANG ---
                Section::make('Keranjang Belanja')
                    ->schema([
                        Repeater::make('items')
                            ->relationship()
                            ->schema([
                                Select::make('product_id')
                                    ->label('Produk')
                                    ->options(\App\Models\Product::pluck('name', 'id'))
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, Set $set) {
                                        $product = \App\Models\Product::find($state);
                                        if ($product) {
                                            // Otomatis isi Harga Jual Retail
                                            $set('unit_price', $product->sell_price_retail);
                                            $set('quantity', 1);
                                        }
                                    }),

                                TextInput::make('quantity')
                                    ->numeric()
                                    ->default(1)
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn ($state, Set $set, Get $get) => 
                                        $set('total_price', $state * $get('unit_price'))
                                    ),

                                TextInput::make('unit_price')
                                    ->label('Harga Satuan')
                                    ->numeric()
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn ($state, Set $set, Get $get) => 
                                        $set('total_price', $state * $get('quantity'))
                                    ),

                                TextInput::make('total_price')
                                    ->label('Subtotal')
                                    ->numeric()
                                    ->readOnly(),
                            ])
                            ->columns(4)
                            ->live()
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                self::updateGrandTotal($get, $set);
                            }),
                    ])->columnSpanFull(),
            ])->columns(3);
    }

    // Fungsi Hitung Grand Total (Sama kayak PO)
    public static function updateGrandTotal(Get $get, Set $set): void
    {
        $items = $get('items');
        $total = 0;
        if ($items) {
            foreach ($items as $item) {
                $total += ($item['quantity'] ?? 0) * ($item['unit_price'] ?? 0);
            }
        }
        $set('total_amount', $total);
    }
}
