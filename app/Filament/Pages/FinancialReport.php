<?php

namespace App\Filament\Pages;

use App\Models\Account;
use App\Models\JournalItem;
use Filament\Infolists\Components\TextEntry;
use Filament\Pages\Page;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use BackedEnum;
use Filament\Support\Icons\Heroicon;

class FinancialReport extends Page implements HasInfolists
{
    use InteractsWithInfolists;

    protected string $view = 'filament.pages.financial-report';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPresentationChartLine;     

    protected static ?string $navigationLabel = 'Laporan Keuangan';
    

    public function getBalance($code, $type = 'debit')
    {
        $account = Account::where('code', $code)->first();
        if (!$account) return 0;

        $debit = JournalItem::where('account_id', $account->id)->sum('debit');
        $credit = JournalItem::where('account_id', $account->id)->sum('credit');

        return $type === 'debit' ? ($debit - $credit) : ($credit - $debit);
    }

    // --- DATA PROVIDER ---
    // Kita siapkan data di sini biar rapi
    public function getReportData(): array
    {
        $kas = $this->getBalance('1101', 'debit');
        $totalAset = $kas;

        $sukarela = $this->getBalance('2102', 'credit');
        $pokok = $this->getBalance('3101', 'credit');
        $wajib = $this->getBalance('3102', 'credit');
        
        // Rumus SHU Berjalan = Aset - (Hutang + Modal)
        $shuBerjalan = $totalAset - ($sukarela + $pokok + $wajib);
        
        $totalPasiva = $sukarela + $pokok + $wajib + $shuBerjalan;
        $isBalanced = $totalAset === $totalPasiva;

        return [
            'kas' => $kas,
            'total_aset' => $totalAset,
            'sukarela' => $sukarela,
            'pokok' => $pokok,
            'wajib' => $wajib,
            'shu_berjalan' => $shuBerjalan,
            'total_pasiva' => $totalPasiva,
            'status' => $isBalanced ? 'BALANCE ✅' : 'NOT BALANCE ❌',
            'selisih' => $totalAset - $totalPasiva,
        ];
    }

    // --- LAYOUT BUILDER (PHP) ---
    public function financialInfolist(Schema $infolist): Schema
    {
        return $infolist
            ->state($this->getReportData()) // Load data dari fungsi di atas
            ->schema([
                // SECTION STATUS
                Section::make('Status Laporan')
                    ->schema([
                        TextEntry::make('status')
                            ->label('Indikator')
                            ->badge()
                            ->color(fn ($state) => str_contains($state, 'NOT') ? 'danger' : 'success'),
                        
                        TextEntry::make('selisih')
                            ->label('Selisih')
                            ->money('IDR')
                            ->visible(fn ($state) => $state != 0)
                            ->color('danger'),
                    ])->columns(2),

                // GRID UTAMA (Neraca T-Shape)
                Grid::make(2)
                    ->schema([
                        // KOLOM KIRI: AKTIVA
                        Section::make('AKTIVA (ASET)')
                            ->description('Harta kekayaan yang dimiliki koperasi')
                            ->icon('heroicon-m-arrow-trending-up')
                            ->schema([
                                TextEntry::make('kas')
                                    ->label('1101 - Kas Teller')
                                    ->money('IDR')
                                    ->size('Large'),
                                
                                // Spacer atau item lain bisa ditambah di sini
                                
                                TextEntry::make('total_aset')
                                    ->label('TOTAL AKTIVA')
                                    ->money('IDR')
                                    ->weight(FontWeight::Bold)
                                    ->size('Large')
                                    ->color('primary')
                                    ->separator(),
                            ])->columnSpan(1),

                        // KOLOM KANAN: PASIVA
                        Section::make('PASIVA (KEWAJIBAN + MODAL)')
                            ->description('Sumber dana (Hutang & Ekuitas)')
                            ->icon('heroicon-m-scale')
                            ->schema([
                                TextEntry::make('sukarela')
                                    ->label('2102 - Simpanan Sukarela')
                                    ->money('IDR'),
                                
                                TextEntry::make('pokok')
                                    ->label('3101 - Simpanan Pokok')
                                    ->money('IDR'),

                                TextEntry::make('wajib')
                                    ->label('3102 - Simpanan Wajib')
                                    ->money('IDR'),

                                TextEntry::make('shu_berjalan')
                                    ->label('3999 - SHU Tahun Berjalan')
                                    ->money('IDR')
                                    ->color(fn ($state) => $state < 0 ? 'danger' : 'success')
                                    ->helperText('Keuntungan/Kerugian sementara'),

                                TextEntry::make('total_pasiva')
                                    ->label('TOTAL PASIVA')
                                    ->money('IDR')
                                    ->weight(FontWeight::Bold)
                                    ->size('Large')
                                    ->color('success')
                                    ->separator(),
                            ])->columnSpan(1),
                    ]),
            ]);
    }
}