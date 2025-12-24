<?php

namespace App\Filament\Resources\Warehouses\Schemas;

use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class WarehouseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
               Section::make('Informasi Gudang')
                    ->schema([
                        Grid::make()
                            ->columns(2)
                            ->schema([
                                TextInput::make('name')
                                    ->label('Nama Gudang')
                                    ->required()
                                    ->placeholder('Contoh: Gudang Pusat Jakarta')
                                    ->maxLength(100),
                                TextInput::make('location')
                                    ->label('Lokasi / Alamat')
                                    ->required()
                                    ->maxLength(255),
                                
                                Select::make('manager_id')
                                    ->label('Kepala Gudang')
                                    ->options(function () {
                                        // Ambil user yang BUKAN member (punya role panel_user/admin)
                                        // Atau sementara ambil semua user dulu
                                        return User::pluck('name', 'id');
                                    })
                                    ->searchable()
                                    ->preload(),
                            ])
                        
                    ])->columnSpanFull(),
            ]);
    }
}
