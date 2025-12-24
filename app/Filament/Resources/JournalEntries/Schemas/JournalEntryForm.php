<?php

namespace App\Filament\Resources\JournalEntries\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class JournalEntryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Bukti Transaksi')
                    ->schema([
                        Grid::make()
                            ->columns(2)
                            ->schema([
                                DatePicker::make('transaction_date')
                                    ->label('Tanggal Transaksi')
                                    ->default(now())
                                    ->required(),
                                TextInput::make('reference_number')
                                    ->label('No. Referensi')
                                    ->placeholder('Contoh: JU-001')
                                    ->maxLength(50),
                                Select::make('accounting_period_id')
                                    ->label('Periode Buku')
                                    ->relationship('period', 'name')
                                    ->default(1) 
                                    ->required(),
                                TextInput::make('description')
                                    ->label('Keterangan')
                                    ->required()
                                    ->maxLength(255),
                            ])
                        
                    ])->columnSpanFull(),

                Section::make('Rincian Jurnal')
                    ->schema([
                        Repeater::make('items')
                            ->relationship()
                            ->schema([
                                Select::make('account_id')
                                    ->label('Akun')
                                    ->options(\App\Models\Account::get()->mapWithKeys(function ($account) {
                                        return [$account->id => $account->code . ' - ' . $account->name];
                                    }))
                                    ->searchable()
                                    ->required()
                                    ->columnSpan(2),
                                    
                                TextInput::make('debit')
                                    ->label('Debit')
                                    ->numeric()
                                    ->default(0)
                                    ->live(onBlur: true) 
                                    ->afterStateUpdated(fn (Set $set) => $set('credit', 0)), // Kalau isi debit, kredit jadi 0
                                    
                                TextInput::make('credit')
                                    ->label('Kredit')
                                    ->numeric()
                                    ->default(0)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (Set $set) => $set('debit', 0)), // Kalau isi kredit, debit jadi 0
                            ])
                            ->columns(4)
                            ->defaultItems(2)
                            ->live()
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                $items = $get('items');
                                $totalDebit = 0;
                                $totalCredit = 0;
                                
                                foreach ($items as $item) {
                                    $totalDebit += $item['debit'] ?? 0;
                                    $totalCredit += $item['credit'] ?? 0;
                                }
                                
                                $set('total_amount', $totalDebit);
                            }),
                            
                        TextInput::make('total_amount')
                            ->label('Total Transaksi')
                            ->readOnly()
                            ->prefix('Rp'),
                    ])->columnSpanFull(),
            ]);
    }
}
