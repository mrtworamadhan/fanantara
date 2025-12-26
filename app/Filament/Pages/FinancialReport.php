<?php

namespace App\Filament\Pages;

use App\Models\AccountingPeriod;
use App\Services\FinancialService;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Pages\Page;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Icons\Heroicon;
use BackedEnum;

class FinancialReport extends Page implements HasInfolists, HasForms
{
    use InteractsWithInfolists;
    use InteractsWithForms;

    protected string $view = 'filament.pages.financial-report';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentCurrencyDollar;
    protected static ?string $navigationLabel = 'Laporan Keuangan';

    public ?int $selected_period_id = null;

    public function mount(): void
    {
        $activePeriod = AccountingPeriod::where('is_closed', false)->latest()->first() 
                        ?? AccountingPeriod::latest()->first();

        $this->selected_period_id = $activePeriod?->id;

        // Sinkronisasi data ke form filter
        $this->form->fill([
            'selected_period_id' => $this->selected_period_id,
        ]);
    }

    public function form(Schema $form): Schema
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        Select::make('selected_period_id')
                            ->label('Pilih Tahun Buku / Periode Akuntansi')
                            ->options(AccountingPeriod::all()->pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->live() // Memicu update Livewire secara real-time
                            ->afterStateUpdated(fn ($state) => $this->selected_period_id = $state)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public function getReportData(): array
    {
        $service = app(FinancialService::class);
    
        $period = AccountingPeriod::find($this->selected_period_id);

        if (!$period) return []; 

        // Panggil semua service dengan melempar OBJEK $period
        $asetLancarTotal = $service->getBalanceByRange('1100', '1199', $period);
        $asetTetapTotal = $service->getBalanceByRange('1200', '1299', $period);
        $totalAset = $asetLancarTotal + $asetTetapTotal;

        $hutangPendekTotal = $service->getBalanceByRange('2100', '2199', $period);
        $hutangPanjangTotal = $service->getBalanceByRange('2200', '2299', $period);
        
        $totalModal = $service->getBalanceByRange('3000', '3999', $period);
        $shuBerjalan = $service->getNetIncome($period);

        $totalPasiva = $hutangPendekTotal + $hutangPanjangTotal + $totalModal + $shuBerjalan;

        $equityChanges = $service->getEquityChanges($period);

        return [
            'period_name' => $period->name,
            'status' => round($totalAset, 2) === round($totalPasiva, 2) ? 'BALANCE ✅' : 'NOT BALANCE ❌',
            'selisih' => abs($totalAset - $totalPasiva),

            // ASET
            'aset_lancar_list' => $service->getAccountDetailsByRange('1100', '1199', $period),
            'aset_lancar_total' => $asetLancarTotal,
            'aset_tetap_list' => $service->getAccountDetailsByRange('1200', '1299', $period),
            'aset_tetap_total' => $asetTetapTotal,
            'total_aset' => $totalAset,

            // PASIVA
            'hutang_list' => $service->getAccountDetailsByRange('2100', '2299', $period),
            'total_hutang' => $hutangPendekTotal + $hutangPanjangTotal,
            'modal_list' => $service->getAccountDetailsByRange('3000', '3999', $period),
            'total_modal' => $totalModal,
            'shu_berjalan' => $shuBerjalan,
            'total_pasiva' => $totalPasiva,

            // EKUITAS
            'equity_changes' => $equityChanges,
            'total_initial_equity' => collect($equityChanges)->sum('initial_balance'),
            'total_ending_equity' => collect($equityChanges)->sum('ending_balance'),
            
            // Tambahkan Data PHU
            'phu' => $service->getPhuData($period),

            // CASHFLOW
            'arus_kas' => $service->getCashFlowData($period),
        ];
    }

    public function financialInfolist(Schema $infolist): Schema
    {
        return $infolist
            ->state($this->getReportData())
            ->schema([
                Section::make('Status Neraca')
                    ->schema([
                        TextEntry::make('status')
                            ->label('Status')
                            ->badge()
                            ->color(fn ($state) => str_contains($state, 'NOT') ? 'danger' : 'success'),
                        TextEntry::make('selisih')->label('Selisih')->money('IDR')->visible(fn ($state) => $state > 0),
                    ])->columns(2),

                Tabs::make('Laporan Utama')
                    ->tabs([
                        Tab::make('Neraca')
                            ->icon('heroicon-o-scale')
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        // SISI KIRI: ASET
                                        Section::make('1. AKTIVA (ASET)')
                                            ->schema([
                                                // Aset Lancar
                                                RepeatableEntry::make('aset_lancar_list')
                                                    ->label('Aset Lancar')
                                                    ->schema([
                                                        Grid::make(2)
                                                            ->schema([
                                                                TextEntry::make('code_name')->label(''),
                                                                TextEntry::make('balance')->label('')->money('IDR')->alignEnd(),
                                                            ]),
                                                    ]),
                                                TextEntry::make('aset_lancar_total')->label('Sub-Total Aset Lancar')->money('IDR')->weight(FontWeight::Bold)->alignEnd(),

                                                // Aset Tetap
                                                RepeatableEntry::make('aset_tetap_list')
                                                    ->label('Aset Tetap')
                                                    ->schema([
                                                        Grid::make(2)
                                                            ->schema([
                                                                TextEntry::make('code_name')->label(''),
                                                                TextEntry::make('balance')->label('')->money('IDR')->alignEnd(),
                                                            ]),
                                                    ]),
                                                TextEntry::make('aset_tetap_total')->label('Sub-Total Aset Tetap')->money('IDR')->weight(FontWeight::Bold)->alignEnd(),

                                                TextEntry::make('total_aset')->label('TOTAL AKTIVA')->money('IDR')->weight(FontWeight::Bold)->size('large')->color('primary')->alignEnd(),
                                            ])->columnSpan(1),

                                        // SISI KANAN: PASIVA
                                        Section::make('2. PASIVA (KEWAJIBAN & EKUITAS)')
                                            ->schema([
                                                // Kewajiban
                                                RepeatableEntry::make('hutang_list')
                                                    ->label('Kewajiban / Hutang')
                                                    ->schema([
                                                        Grid::make(2)
                                                            ->schema([
                                                                TextEntry::make('code_name')->label(''),
                                                                TextEntry::make('balance')->label('')->money('IDR')->alignEnd(),
                                                            ]),
                                                    ]),
                                                
                                                // Modal & SHU
                                                RepeatableEntry::make('modal_list')
                                                    ->label('Ekuitas / Modal Anggota')
                                                    ->schema([
                                                        Grid::make(2)
                                                            ->schema([
                                                                TextEntry::make('code_name')->label(''),
                                                                TextEntry::make('balance')->label('')->money('IDR')->alignEnd(),
                                                            ]),
                                                    ]),

                                                TextEntry::make('shu_berjalan')->label('SHU Tahun Berjalan')->money('IDR')->weight(FontWeight::Bold)->color('success')->alignEnd(),

                                                TextEntry::make('total_pasiva')->label('TOTAL PASIVA')->money('IDR')->weight(FontWeight::Bold)->size('large')->color('success')->alignEnd(),
                                            ])->columnSpan(1),
                                    ]),
                            ]),
                        Tab::make('Laba Rugi PHU')
                            ->icon('heroicon-o-document-text')
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        // SISI PENDAPATAN
                                        Section::make('PENDAPATAN')
                                            ->schema([
                                                RepeatableEntry::make('phu.revenue_list')
                                                    ->label('')
                                                    ->schema([
                                                        Grid::make(2)
                                                            ->schema([
                                                                TextEntry::make('code_name')->label(''),
                                                                TextEntry::make('balance')->label('')->money('IDR')->alignEnd(),
                                                            ]),
                                                    ]),
                                                TextEntry::make('phu.total_revenue')
                                                    ->label('TOTAL PENDAPATAN')
                                                    ->money('IDR')->weight(FontWeight::Bold)->color('primary')->alignEnd(),
                                            ])->columnSpan(1),

                                        // SISI BEBAN
                                        Section::make('BEBAN / BIAYA')
                                            ->schema([
                                                RepeatableEntry::make('phu.expense_list')
                                                    ->label('')
                                                    ->schema([
                                                        Grid::make(2)
                                                            ->schema([
                                                                TextEntry::make('code_name')->label(''),
                                                                TextEntry::make('balance')->label('')->money('IDR')->alignEnd(),
                                                            ]),
                                                    ]),
                                                TextEntry::make('phu.total_expense')
                                                    ->label('TOTAL BEBAN')
                                                    ->money('IDR')->weight(FontWeight::Bold)->color('danger')->alignEnd(),
                                            ])->columnSpan(1),
                                    ]),

                                Section::make('HASIL USAHA NETTO (SHU)')
                                    ->schema([
                                        TextEntry::make('phu.net_shu')
                                            ->label('SISA HASIL USAHA PERIODE INI')
                                            ->money('IDR')
                                            ->weight(FontWeight::Bold)
                                            ->size('large')
                                            ->color(fn($state) => $state >= 0 ? 'success' : 'danger')
                                            ->alignCenter(),
                                    ]), 
                            ]),
                        Tab::make('Arus Kas & Ekuitas')
                            ->icon('heroicon-o-arrows-right-left')
                            ->schema([
                                Section::make('3. LAPORAN PERUBAHAN EKUITAS')
                                    ->description('Mutasi Modal Koperasi Selama Periode Berjalan')
                                    ->schema([
                                        RepeatableEntry::make('equity_changes')
                                            ->label('')
                                            ->schema([
                                                Grid::make(5)
                                                    ->schema([
                                                        TextEntry::make('account_name')
                                                            ->label('Akun Ekuitas')
                                                            ->weight(FontWeight::Bold),
                                                        TextEntry::make('initial_balance')
                                                            ->label('Saldo Awal')
                                                            ->money('IDR')
                                                            ->alignEnd(),
                                                        TextEntry::make('addition')
                                                            ->label('Penambahan (+)')
                                                            ->money('IDR')
                                                            ->color('success')
                                                            ->alignEnd(),
                                                        TextEntry::make('reduction')
                                                            ->label('Pengurangan (-)')
                                                            ->money('IDR')
                                                            ->color('danger')
                                                            ->alignEnd(),
                                                        TextEntry::make('ending_balance')
                                                            ->label('Saldo Akhir')
                                                            ->money('IDR')
                                                            ->weight(FontWeight::Bold)
                                                            ->alignEnd(),
                                                    ]),
                                            ]),

                                        Grid::make(2)
                                            ->schema([
                                                TextEntry::make('total_initial_equity')
                                                    ->label('TOTAL EKUITAS AWAL')
                                                    ->money('IDR')
                                                    ->weight(FontWeight::Bold),
                                                TextEntry::make('total_ending_equity')
                                                    ->label('TOTAL EKUITAS AKHIR')
                                                    ->money('IDR')
                                                    ->weight(FontWeight::Bold)
                                                    ->color('primary')
                                                    ->alignEnd(),
                                            ]),
                                    ]), 
                                Section::make('4. LAPORAN ARUS KAS (Metode Tidak Langsung)')
                                    ->schema([
                                        Grid::make(3)
                                            ->schema([
                                                // OPERASIONAL
                                                Group::make([
                                                    TextEntry::make('arus_kas.operating.net_income')->label('SHU Bersih')->money('IDR'),
                                                    TextEntry::make('arus_kas.operating.depreciation')->label('Penyusutan (+)')->money('IDR'),
                                                    TextEntry::make('arus_kas.operating.delta_assets')->label('Perubahan Piutang/Persediaan')->money('IDR'),
                                                    TextEntry::make('arus_kas.operating.delta_liabilities')->label('Perubahan Hutang Usaha')->money('IDR'),
                                                    TextEntry::make('arus_kas.operating.total')->label('Total Arus Kas Operasional')->money('IDR')->weight(FontWeight::Bold)->color('primary'),
                                                ])->columnSpan(1),

                                                // INVESTASI & PENDANAAN
                                                Group::make([
                                                    TextEntry::make('arus_kas.investing.total')->label('Arus Kas Investasi (Aset Tetap)')->money('IDR')->weight(FontWeight::Bold),
                                                    TextEntry::make('arus_kas.financing.total')->label('Arus Kas Pendanaan (Simpanan)')->money('IDR')->weight(FontWeight::Bold),
                                                ])->columnSpan(1),

                                                // SUMMARY
                                                Group::make([
                                                    TextEntry::make('arus_kas.initial_cash')->label('Saldo Kas Awal Periode')->money('IDR'),
                                                    TextEntry::make('arus_kas.net_increase')->label('Kenaikan/Penurunan Kas Netto')->money('IDR')->weight(FontWeight::Bold)->color(fn($state) => $state >= 0 ? 'success' : 'danger'),
                                                    TextEntry::make('arus_kas.final_cash')->label('Saldo Kas Akhir Periode')->money('IDR')->weight(FontWeight::Bold)->size('large'),
                                                ])->columnSpan(1),
                                            ]),
                                    ]),
                            ]),
                    ]),
            ]);
    }
}