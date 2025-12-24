<?php

namespace App\Filament\Resources\JournalEntries\Pages;

use App\Filament\Resources\JournalEntries\JournalEntryResource;
use App\Models\Account;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Resources\Pages\Page;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use BackedEnum;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class FinancialReport extends Page implements HasForms, HasInfolists
{
    use InteractsWithForms;
    use InteractsWithInfolists;

    protected static string $resource = JournalEntryResource::class;

    protected string $view = 'filament.resources.journal-entries.pages.financial-report';

    protected static ?string $title = 'Laporan Keuangan';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowsRightLeft;

    protected static string | UnitEnum | null $navigationGroup = 'Keuangan';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationLabel = 'Laporan Keuangan';

    protected static ?string $recordTitleAttribute = 'name';

    public ?array $filterData = [];

    public ?array $reportData = [];

    public ?string $start_date = null;
    public ?string $end_date = null;

    public function mount(): void
    {
        $this->form->fill([
            'quick_filter' => 'annual',
            'start_date' => now()->startOfYear()->format('Y-m-d'),
            'end_date' => now()->endOfYear()->format('Y-m-d'),
        ]);

        $this->calculateReport();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Filter Periode')
                    ->schema([
                        Select::make('quick_filter')
                            ->label('Pilih Periode')
                            ->options([
                                'annual' => 'Tahun Ini (' . date('Y') . ')',
                                's1' => 'Semester 1 (Jan - Jun)',
                                's2' => 'Semester 2 (Jul - Des)',
                                'q1' => 'Kuartal 1 (Jan - Mar)',
                                'q2' => 'Kuartal 2 (Apr - Jun)',
                                'q3' => 'Kuartal 3 (Jul - Sep)',
                                'q4' => 'Kuartal 4 (Okt - Des)',
                                'custom' => 'Custom Tanggal...',
                            ])
                            ->default('annual')
                            ->selectablePlaceholder(false)
                            ->live()
                            ->afterStateUpdated(function ($state, Set $set) {
                                $year = date('Y');
                                
                                match ($state) {
                                    'annual' => [
                                        $set('start_date', $year . '-01-01'),
                                        $set('end_date', $year . '-12-31')
                                    ],
                                    's1' => [
                                        $set('start_date', $year . '-01-01'),
                                        $set('end_date', $year . '-06-30')
                                    ],
                                    's2' => [
                                        $set('start_date', $year . '-07-01'),
                                        $set('end_date', $year . '-12-31')
                                    ],
                                    'q1' => [
                                        $set('start_date', $year . '-01-01'),
                                        $set('end_date', $year . '-03-31')
                                    ],
                                    'q2' => [
                                        $set('start_date', $year . '-04-01'),
                                        $set('end_date', $year . '-06-30')
                                    ],
                                    'q3' => [
                                        $set('start_date', $year . '-07-01'),
                                        $set('end_date', $year . '-09-30')
                                    ],
                                    'q4' => [
                                        $set('start_date', $year . '-10-01'),
                                        $set('end_date', $year . '-12-31')
                                    ],
                                    default => null,
                                };
                            }),

                        DatePicker::make('start_date')
                            ->label('Dari')
                            ->required()
                            ->live()
                            ->afterStateUpdated(fn (Set $set) => $set('quick_filter', 'custom')), // Kalau user ganti tanggal, dropdown jadi 'Custom'

                        DatePicker::make('end_date')
                            ->label('Sampai')
                            ->required()
                            ->live()
                            ->afterStateUpdated(fn (Set $set) => $set('quick_filter', 'custom')),
                    ])
                    ->columns(3)
                    ->statePath('filterData')
            ]);
    }

    public function updatedFilterData()
    {
        $this->calculateReport();
    }

    public function reportInfolist(Schema $schema): Schema
    {
        return $schema
            ->record($this->reportData)
            ->components([
                
                Section::make('Laporan Posisi Keuangan (Neraca)')
                    ->schema([
                            Section::make('ASET (HARTA)')
                                ->schema([
                                    RepeatableEntry::make('assets')
                                        ->label('')
                                        ->schema([
                                            TextEntry::make('code_name')->hiddenLabel(),
                                            TextEntry::make('balance')->money('IDR')->hiddenLabel()->alignRight(),
                                        ])->columns(2)->contained(false),
                                    
                                    TextEntry::make('total_assets')
                                        ->label('TOTAL ASET')
                                        ->money('IDR')
                                        ->weight('bold')
                                        ->size('lg')
                                        ->alignRight(),
                                ])->grow(false),

                            Group::make([
                                Section::make('LIABILITAS (HUTANG)')
                                    ->components([
                                        RepeatableEntry::make('liabilities')
                                            ->label('')
                                            ->schema([
                                                TextEntry::make('code_name')->hiddenLabel(),
                                                TextEntry::make('balance')->money('IDR')->hiddenLabel()->alignRight(),
                                            ])->columns(2)->contained(false),
                                        
                                        TextEntry::make('total_liabilities')
                                            ->label('Total Liabilitas')
                                            ->money('IDR')
                                            ->weight('bold')
                                            ->alignRight(),
                                    ]),

                                Section::make('EKUITAS (MODAL)')
                                    ->schema([
                                        RepeatableEntry::make('equity')
                                            ->label('')
                                            ->schema([
                                                TextEntry::make('code_name')->hiddenLabel(),
                                                TextEntry::make('balance')->money('IDR')->hiddenLabel()->alignRight(),
                                            ])->columns(2)->contained(false),

                                        TextEntry::make('total_equity')
                                            ->label('Total Ekuitas')
                                            ->money('IDR')
                                            ->weight('bold')
                                            ->alignRight(),
                                    ]),
                                
                                TextEntry::make('total_pasiva')
                                    ->label('TOTAL KEWAJIBAN & EKUITAS')
                                    ->money('IDR')
                                    ->weight('bold')
                                    ->size('lg')
                                    ->alignRight()
                                    ->color(fn ($state, $record) => 
                                        ($record['total_assets'] == $state) ? 'success' : 'danger'
                                    ),
                            ]),
                    ]),

                Section::make('Laporan Laba Rugi (SHU)')
                    ->schema([
                        Grid::make(2)->schema([
                            Group::make([
                                TextEntry::make('label_rev')->label('Pendapatan')->default('PENDAPATAN')->weight('bold'),
                                RepeatableEntry::make('revenue')
                                    ->label('')
                                    ->schema([
                                        TextEntry::make('name')->hiddenLabel(),
                                        TextEntry::make('balance')->money('IDR')->hiddenLabel()->color('success')->alignRight(),
                                    ])->columns(2)->contained(false),
                                TextEntry::make('total_revenue')->label('Total Pendapatan')->money('IDR')->weight('bold')->alignRight(),
                            ]),

                            Group::make([
                                TextEntry::make('label_exp')->label('Beban')->default('BEBAN & BIAYA')->weight('bold'),
                                RepeatableEntry::make('expenses')
                                    ->label('')
                                    ->schema([
                                        TextEntry::make('name')->hiddenLabel(),
                                        TextEntry::make('balance')->money('IDR')->hiddenLabel()->color('danger')->alignRight(),
                                    ])->columns(2)->contained(false),
                                TextEntry::make('total_expenses')->label('Total Beban')->money('IDR')->weight('bold')->alignRight(),
                            ]),
                        ]),

                        Section::make('HASIL AKHIR')
                            ->schema([
                                TextEntry::make('net_income')
                                    ->label('SHU BERSIH (LABA/RUGI)')
                                    ->money('IDR')
                                    ->size('xl')
                                    ->weight('black')
                                    ->alignCenter()
                                    ->color(fn ($state) => $state >= 0 ? 'success' : 'danger'),
                            ])
                    ]),
            ]);
    }

    public function calculateReport()
    {
        $data = $this->form->getState();
        $startDate = $data['start_date'] ?? now()->startOfYear();
        $endDate = $data['end_date'] ?? now()->endOfYear();

        $accounts = Account::with(['journalItems' => function ($query) use ($startDate, $endDate) {
            $query->whereHas('journalEntry', function ($q) use ($startDate, $endDate) {
                $q->whereBetween('transaction_date', [$startDate, $endDate]);
            });
        }])->get();

        $report = [
            'assets' => [], 'liabilities' => [], 'equity' => [],
            'revenue' => [], 'expenses' => [],
            'total_assets' => 0, 'total_liabilities' => 0, 'total_equity' => 0,
            'total_revenue' => 0, 'total_expenses' => 0, 'total_pasiva' => 0,
            'net_income' => 0,
        ];

        foreach ($accounts as $account) {
            $debit = $account->journalItems->sum('debit');
            $credit = $account->journalItems->sum('credit');
            $balance = 0;

            if (in_array($account->type, ['asset', 'expense'])) {
                $balance = $debit - $credit;
            } else {
                $balance = $credit - $debit;
            }

            if ($balance != 0) {
                $item = [
                    'code_name' => $account->code . ' - ' . $account->name,
                    'name' => $account->name,
                    'balance' => $balance
                ];

                $key = match($account->type) {
                    'asset' => 'assets',
                    'liability' => 'liabilities',
                    'equity' => 'equity',
                    'revenue' => 'revenue',
                    'expense' => 'expenses',
                };

                $report[$key][] = $item;
                $report['total_' . $key] += $balance;
            }
        }

        $report['net_income'] = $report['total_revenue'] - $report['total_expenses'];

        if ($report['net_income'] != 0) {
            $report['equity'][] = [
                'code_name' => '3301 - SHU Tahun Berjalan',
                'balance' => $report['net_income']
            ];
            $report['total_equity'] += $report['net_income'];
        }

        $report['total_pasiva'] = $report['total_liabilities'] + $report['total_equity'];

        $this->reportData = $report;
    }
}
