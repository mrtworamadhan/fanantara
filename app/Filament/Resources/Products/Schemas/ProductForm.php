<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make()
                    ->schema([
                        Section::make('Detail Produk')
                            ->schema([
                                FileUpload::make('image')
                                    ->label('Foto Produk')
                                    ->image() 
                                    ->imageEditor()
                                    ->directory('products') 
                                    ->disk('public')
                                    ->visibility('public')
                                    ->columnSpanFull(),
                                TextInput::make('name')
                                    ->label('Nama Produk')
                                    ->required()
                                    ->maxLength(255),
                                    
                                TextInput::make('sku')
                                    ->label('SKU (Kode Barang)')
                                    ->unique(ignoreRecord: true)
                                    ->required(),
                                    
                                TextInput::make('unit')
                                    ->label('Satuan')
                                    ->placeholder('Pcs, Kg, Karton')
                                    ->required(),
                                    
                                Select::make('supplier_id')
                                    ->label('Supplier (Anggota Produsen)')
                                    ->options(function () {
                                        return \App\Models\Member::where('type', 'institution')
                                            ->get()
                                            ->mapWithKeys(function ($member) {
                                                // Kita pakai Accessor 'name' yang sudah kita fix tadi
                                                return [$member->id => $member->name];
                                            });
                                    })
                                    ->searchable()
                                    ->required(),
                            ])->columns(2),
                    ]),

                Group::make()
                    ->schema([
                        Section::make('Harga & Status')
                            ->schema([
                                TextInput::make('base_price')
                                    ->label('Harga Beli (HPP)')
                                    ->helperText('Harga dari Supplier')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->required(),
                                    
                                TextInput::make('sell_price_wholesale')
                                    ->label('Harga Jual Agen/Grosir')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->required(),
                                    
                                TextInput::make('sell_price_retail')
                                    ->label('Harga Jual Eceran')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->required(),
                                    
                                Toggle::make('is_active')
                                    ->label('Aktif Dijual')
                                    ->default(true),
                            ]),
                    ]),
            ]);
    }
}
