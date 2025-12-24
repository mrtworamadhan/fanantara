<?php

namespace App\Filament\Resources\Accounts\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AccountForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Detail Akun')
                    ->schema([
                        TextInput::make('code')
                            ->label('Kode Akun')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(20)
                            ->helperText('Contoh: 1-1001 (Sesuai Standar SAK EP)'),
                            
                        TextInput::make('name')
                            ->label('Nama Akun')
                            ->required()
                            ->maxLength(100),
                            
                        Select::make('type')
                            ->label('Kategori')
                            ->options([
                                'asset' => 'ASET (Harta)',
                                'liability' => 'LIABILITAS (Hutang)',
                                'equity' => 'EKUITAS (Modal)',
                                'revenue' => 'PENDAPATAN',
                                'expense' => 'BEBAN',
                            ])
                            ->required(),
                            
                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true),
                    ])->columns(2),
            ]);
    }
}
