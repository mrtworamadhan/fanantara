<?php

namespace App\Filament\Resources\SavingTransactions\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;

class SavingTransactionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Teller Koperasi')
                    ->schema([
                        Grid::make()
                            ->columns(2)
                            ->schema([
                                Select::make('member_id')
                                    ->label('Anggota')
                                    ->options(\App\Models\Member::get()->pluck('name', 'id'))
                                    ->searchable()
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                            self::updateAmount($get, $set);
                                        }),
                                
                                Placeholder::make('saldo_info')
                                    ->label('Posisi Saldo Saat Ini')
                                    ->content(fn (Get $get) => self::getSaldoHtml($get('member_id')))
                                    ->visible(fn (Get $get) => filled($get('member_id'))) // Muncul cuma kalau member dipilih
                                    ->columnSpan(1),

                                Select::make('saving_type_id')
                                    ->label('Jenis Simpanan')
                                    ->options(\App\Models\SavingType::pluck('name', 'id'))
                                    ->required()
                                    ->live()
                                    ->live()
                                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                            self::updateAmount($get, $set);
                                        }),

                                Select::make('type')
                                    ->label('Jenis Transaksi')
                                    ->options([
                                        'deposit' => 'Setoran (Uang Masuk)',
                                        'withdrawal' => 'Penarikan (Uang Keluar)',
                                    ])
                                    ->default('deposit')
                                    ->required()
                                    ->live(), 
                                    
                                TextInput::make('amount')
                                    ->label('Nominal (Rp)')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->required()
                                    ->minValue(50000),

                                DatePicker::make('transaction_date')
                                    ->label('Tanggal')
                                    ->default(now())
                                    ->required(),

                                Textarea::make('notes')
                                    ->label('Keterangan')
                                    ->maxLength(255),
                            ])
                        
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function getSaldoHtml($memberId): HtmlString
    {
        if (! $memberId) return new HtmlString('-');

        $accounts = \App\Models\SavingAccount::with('savingType')
            ->where('member_id', $memberId)
            ->get();

        if ($accounts->isEmpty()) {
            return new HtmlString('<span class="text-sm text-gray-500 italic">Belum ada rekening simpanan.</span>');
        }

        $html = '<div class="grid grid-cols-1 gap-2 text-sm">';
        
        foreach ($accounts as $acc) {
            $namaSimpanan = $acc->savingType->name ?? 'Unknown';
            $saldo = number_format($acc->balance, 0, ',', '.');
            
            $colorClass = 'text-gray-600'; 
            $badge = '';

            if (stripos($namaSimpanan, 'Sukarela') !== false) {
                $colorClass = 'text-success-600 font-bold';
                $badge = '<span class="text-xs bg-success-50 text-success-700 px-1 rounded ml-1">Bisa Ditarik</span>';
            }

            $html .= "
                <div class='flex justify-between items-center border-b border-gray-100 pb-1'>
                    <span class='text-gray-500'>{$namaSimpanan}:</span>
                    <span class='{$colorClass}'>Rp {$saldo} {$badge}</span>
                </div>
            ";
        }

        $html .= '</div>';

        return new HtmlString($html);
    }

    public static function updateAmount(Get $get, Set $set): void
    {
        $memberId = $get('member_id');
        $typeId   = $get('saving_type_id');

        if ($memberId && $typeId) {
            $member = \App\Models\Member::find($memberId);
            $savingType = \App\Models\SavingType::find($typeId);

            if ($member && $savingType) {
                if ($member->type === 'institution') {
                    $nominal = $savingType->amount_institution;
                } else {
                    $nominal = $savingType->amount_individual;
                }

                if ($nominal > 0) {
                    $set('amount', $nominal);
                }
            }
        }
    }
    
}
