<?php

namespace App\Filament\Resources\Purchases\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\RawJs;

class PurchaseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make()
                ->schema([
                    Section::make('Informasi Pembelian')
                        ->schema([
                            TextInput::make('purchase_number')
                                ->label('No PO')
                                ->default('PO-' . date('YmdHis'))
                                ->readOnly()
                                ->required(),
                            DatePicker::make('purchase_date')
                                ->label('Tanggal Order')
                                ->default(now())
                                ->required(),
                            Select::make('supplier_id')
                                ->label('Supplier')
                                ->options(\App\Models\Member::where('type', 'institution')->get()->pluck('name', 'id'))
                                ->searchable()
                                ->required(),
                            Select::make('warehouse_id')
                                ->label('Masuk ke Gudang')
                                ->relationship('warehouse', 'name')
                                ->required(),
                            Select::make('status')
                                ->options([
                                    'draft' => 'Draft',
                                    'ordered' => 'Ordered (Dipesan)',
                                    'received' => 'Received (Diterima)',
                                    'cancelled' => 'Cancelled',
                                ])
                                ->default('draft')
                                ->required(),
                            Select::make('payment_status')
                                ->label('Status Pembayaran')
                                ->options([
                                    'unpaid' => 'Tempo (Hutang)',
                                    'paid' => 'Lunas (Tunai)',
                                ])
                                ->default('unpaid')
                                ->required(),
                        ])->columns(2),
                ])->columnSpan(2),
            
            Group::make()
                ->schema([
                    Section::make('Rekapitulasi')
                        ->schema([
                            TextInput::make('total_amount')
                                ->label('Grand Total')
                                ->prefix('Rp')
                                ->mask(RawJs::make('$money($input)'))
                                ->stripCharacters(',')
                                ->numeric()
                                ->readOnly() 
                                ->default(0),
                        ]),
                ])->columnSpan(1),

            Section::make('Daftar Barang')
                ->schema([
                    Repeater::make('items')
                        ->relationship()
                        ->schema([
                            Select::make('product_id')
                                ->label('Produk')
                                ->options(\App\Models\Product::pluck('name', 'id'))
                                ->required()
                                ->reactive()
                                ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                    $product = \App\Models\Product::find($state);
                                    if ($product) {
                                        $set('unit_cost', $product->base_price);
                                        $set('quantity', 1);
                                    }
                                }),

                            TextInput::make('quantity')
                                ->numeric()
                                ->default(1)
                                ->required()
                                ->live(onBlur: true)
                                ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                    $total = $state * $get('unit_cost');
                                    $set('total_cost', $total);
                                }),

                            TextInput::make('unit_cost')
                                ->label('Harga Beli')
                                ->prefix('Rp')
                                ->mask(RawJs::make('$money($input)'))
                                ->stripCharacters(',')
                                ->numeric()
                                ->required()
                                ->live(onBlur: true)
                                ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                    $total = $state * $get('quantity');
                                    $set('total_cost', $total);
                                }),

                            TextInput::make('total_cost')
                                ->label('Subtotal')
                                ->prefix('Rp')
                                ->mask(RawJs::make('$money($input)'))
                                ->stripCharacters(',')
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

    public static function updateGrandTotal(Get $get, Set $set): void
    {
        $items = $get('items');
        $total = 0;

        if ($items) {
            foreach ($items as $item) {
                $qty = intval($item['quantity'] ?? 0);
                $cost = floatval($item['unit_cost'] ?? 0);
                $total += $qty * $cost;
            }
        }

        $set('total_amount', $total);
    }
}
