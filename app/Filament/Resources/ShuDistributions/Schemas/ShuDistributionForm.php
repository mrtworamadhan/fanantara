<?php

namespace App\Filament\Resources\ShuDistributions\Schemas;

use App\Models\AccountingPeriod;
use App\Models\ShuAllocation;
use App\Services\FinancialService;
use Filament\Actions\Action;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class ShuDistributionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Parameter Utama')
                    ->schema([
                        Select::make('accounting_period_id')
                            ->relationship(
                                    'period',
                                    'name',
                                    fn ($query) => $query->where('is_closed', false)
                                )
                            ->label('Tahun Buku')
                            ->required()
                            ->live() 
                            ->afterStateUpdated(function ($state, Set $set) {
                                if (!$state) return;

                                $period = AccountingPeriod::find($state);
                                
                                if ($period) {
                                    $service = app(FinancialService::class);
                                    $grossShu = $service->getNetIncome($period);
                                    
                                    $set('total_shu', $grossShu);
                                }
                            }),

                        // TextInput::make('total_shu')
                        //     ->label('Total SHU Berjalan (Laba Berjalan)')
                        //     ->numeric()
                        //     ->prefix('Rp')
                        //     ->default(fn () => app(FinancialService::class)->getNetIncome())
                        //     ->required()
                        //     ->live(onBlur: true)
                        //     ->afterStateUpdated(fn ($state, Set $set, Get $get) => self::updateAllAmounts($set, $get)),
                        Grid::make(3)
                            ->schema([
                                TextInput::make('total_shu')
                                    ->label('Total SHU (Gross)')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->readOnly()
                                    ->dehydrated(),

                                TextInput::make('tax_amount')
                                    ->label('Pajak (PPh)')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->default(0)
                                    ->live()
                                    ->afterStateUpdated(fn ($get, $set) => self::calculateNetShu($get, $set)),

                                TextInput::make('net_shu_to_distribute')
                                    ->label('SHU Bersih yang Dibagikan')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->readOnly()
                                    ->helperText('Rumus: Gross SHU - Pajak')
                                    ->id('net_shu')
                                    ->dehydrated(),
                            ]),
                        Hidden::make('created_by')
                            ->default(fn () => auth()->id()),
                    ])->columnSpanFull(),

                Section::make('Distribusi Alokasi SHU')
                    ->headerActions([

                            Action::make('calculate_nominal')
                                ->label('Hitung Nominal Otomatis')
                                ->icon('heroicon-m-calculator')
                                ->color('success')
                                ->hidden(fn (string $operation): bool => in_array($operation, ['edit', 'view']))
                                ->action(function (Set $set, Get $get) {
                                    $totalShu = (float) $get('net_shu_to_distribute');
                                    $items = $get('allocation_results') ?? [];
                                    
                                    foreach ($items as $key => $item) {
                                        $percentage = (float) ($item['percentage'] ?? 0);
                                        $nominal = $totalShu * ($percentage / 100);
                                        
                                        $set("allocation_results.{$key}.amount", $nominal);
                                    }

                                    \Filament\Notifications\Notification::make()
                                        ->title('Nominal Berhasil Dihitung')
                                        ->success()
                                        ->send();
                                })
                    ])
                    ->description('Daftar alokasi di bawah ini ditarik otomatis dari Master Alokasi SHU.')
                    ->schema([
                        Repeater::make('allocation_results')
                            ->label('Daftar Pembagian')
                            ->schema([
                                Select::make('shu_allocation_id')
                                    ->label('Kategori Alokasi')
                                    ->options(ShuAllocation::where('is_active', true)->pluck('name', 'id'))
                                    ->required()
                                    ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                    ->columnSpan(2)
                                    ->live()
                                    ->afterStateUpdated(function ($state, Set $set) {
                                        $alloc = ShuAllocation::find($state);
                                        $set('percentage', $alloc?->percentage ?? 0);
                                        $set('name', $alloc?->name ?? ''); // Simpan nama untuk snapshot JSON
                                    }),

                                TextInput::make('percentage')
                                    ->label('Porsi (%)')
                                    ->numeric()
                                    ->suffix('%')
                                    ->required()
                                    ->columnSpan(1),

                                TextInput::make('amount')
                                    ->label('Nominal Rupiah (Auto)')
                                    ->prefix('Rp')
                                    ->numeric()
                                    ->readOnly()
                                    ->columnSpan(2),
                                
                                Hidden::make('name'), // Simpan nama kategori ke JSON agar historis aman
                            ])
                            ->columns(5)
                            ->default(function () {
                                return ShuAllocation::where('is_active', true)
                                    ->get()
                                    ->map(fn ($a) => [
                                        'shu_allocation_id' => $a->id,
                                        'name' => $a->name,
                                        'percentage' => $a->percentage,
                                    ])
                                    ->toArray();
                            })
                            ->reorderable(false)
                            ->addActionLabel('Tambah Alokasi Lain'),
                            
                        Placeholder::make('total_percentage_display')
                            ->label('Total Persentase Terpakai')
                            ->content(function (Get $get) {
                                $total = collect($get('allocation_results'))->sum('percentage');
                                $color = $total == 100 ? 'text-success-600' : 'text-danger-600';
                                return new \Illuminate\Support\HtmlString("<span class='font-bold {$color}'>{$total}% / 100%</span>");
                            }),
                    ])->columnSpanFull(),
            ]);
    }

    /**
     * Logic Perhitungan Nominal Dinamis untuk semua item di Repeater
     */
    public static function updateAllAmounts(Set $set, Get $get)
    {
        $totalShu = (float) $get('total_shu');
        $items = $get('allocation_items') ?? [];
        
        foreach ($items as $key => $item) {
            $percentage = (float) ($item['percentage'] ?? 0);
            $amount = $totalShu * ($percentage / 100);
            
            $set("allocation_items.{$key}.amount", $amount);
        }
    }
    public static function calculateNetShu($get, $set)
    {
        $gross = (float) $get('total_shu');
        $tax = (float) $get('tax_amount');
        
        $net = $gross - $tax;
        
        $set('net_shu_to_distribute', $net);
    }
}