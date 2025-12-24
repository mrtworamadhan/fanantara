<?php

namespace App\Filament\Pages;

use App\Models\Order;
use App\Models\Purchase;
use Filament\Pages\Page;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Split;
use BackedEnum;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;


class DebtReceivableReport extends Page implements HasInfolists
{
    use InteractsWithInfolists;
    use HasPageShield;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBookOpen;    
    protected string $view = 'filament.pages.debt-receivable-report';

    protected static ?string $recordTitleAttribute = 'name';
    protected static string | UnitEnum | null $navigationGroup = 'Keuangan';
    protected static ?string $navigationLabel = 'Buku Hutang & Piutang';
    protected static ?string $title = 'Buku Pembantu Hutang & Piutang';
    protected static ?int $navigationSort = 3;


    public ?array $receivableData = [];
    public ?array $payableData = [];

    public function mount(): void
    {
        $this->calculateData();
    }

    public function calculateData()
    {
        $orders = Order::with('member')
            ->where('payment_status', '!=', 'paid')
            ->where('status', 'completed') // Hanya yang sudah dikirim
            ->get();

        // Grouping per Member biar rapi (Si A total hutangnya berapa)
        $groupedOrders = $orders->groupBy('member_id')->map(function ($memberOrders) {
            $member = $memberOrders->first()->member;
            $totalDebt = $memberOrders->sum(fn($o) => $o->remaining_balance);
            
            return [
                'name' => $member->name . ' (' . ucfirst($member->type) . ')',
                'count' => $memberOrders->count() . ' Transaksi',
                'total' => $totalDebt,
                'details' => $memberOrders->map(fn($o) => 
                    "No: {$o->order_number} | Sisa: Rp " . number_format($o->remaining_balance, 0)
                )->implode('<br>')
            ];
        })->values()->toArray();

        $this->receivableData = $groupedOrders;

        // 2. REKAP HUTANG (PAYABLES) - DARI PURCHASE
        // Ambil PO yang belum kita bayar lunas ke Supplier
        // Asumsi: Kita belum ada tabel Supplier khusus, jadi kita list per PO saja
        $purchases = Purchase::where('payment_status', '!=', 'paid')
            ->where('status', 'received')
            ->get()
            ->map(function ($po) {
                return [
                    'number' => $po->purchase_number,
                    'date' => $po->created_at->format('d M Y'),
                    'total' => $po->remaining_balance
                ];
            })->toArray();

        $this->payableData = $purchases;
    }

    public function reportInfolist(Schema $infolist): Schema
    {
        return $infolist
            ->schema([
                    Grid::make()
                        ->columns(2)
                        ->schema([
                            Section::make('BUKU PIUTANG (Tagihan Anggota)')
                                ->description('Daftar anggota yang belum melunasi belanjaan.')
                                ->icon('heroicon-m-user-group')
                                ->schema([
                                    TextEntry::make('total_receivable')
                                        ->label('TOTAL PIUTANG USAHA')
                                        ->state(fn() => collect($this->receivableData)->sum('total'))
                                        ->money('IDR')
                                        ->size('lg')
                                        ->weight('black')
                                        ->color('primary'),

                                    RepeatableEntry::make('receivableData')
                                        ->label('')
                                        ->state($this->receivableData)
                                        ->schema([
                                            Grid::make(2)->schema([
                                                TextEntry::make('name')
                                                    ->weight('bold')
                                                    ->icon('heroicon-m-user'),
                                                TextEntry::make('total')
                                                    ->money('IDR')
                                                    ->alignRight()
                                                    ->color('danger')
                                                    ->weight('bold'),
                                            ]),
                                            TextEntry::make('details')
                                                ->html()
                                                ->size('xs')
                                                ->color('gray'),
                                        ])
                                        ->contained(false)
                                ]),

                            // --- KOLOM KANAN: BUKU HUTANG (KITA NGUTANG KE SUPPLIER) ---
                            Section::make('BUKU HUTANG (Kewajiban Koperasi)')
                                ->description('Daftar PO Supplier yang belum kita lunasi.')
                                ->icon('heroicon-m-building-storefront')
                                ->schema([
                                    // Total Hutang Header
                                    TextEntry::make('total_payable')
                                        ->label('TOTAL HUTANG DAGANG')
                                        ->state(fn() => collect($this->payableData)->sum('total'))
                                        ->money('IDR')
                                        ->size('lg')
                                        ->weight('black')
                                        ->color('danger'),

                                    // List PO
                                    RepeatableEntry::make('payableData')
                                        ->label('')
                                        ->state($this->payableData)
                                        ->schema([
                                            Grid::make(2)->schema([
                                                TextEntry::make('number')
                                                    ->label('No PO')
                                                    
                                                    ->weight('bold'),
                                                TextEntry::make('total')
                                                    ->alignCenter()
                                                    ->money('IDR')
                                                    ->alignRight()
                                                    ->color('danger'),
                                            ]),
                                            TextEntry::make('date')
                                                ->label('Tanggal PO')
                                                ->size('xs')
                                                ->icon('heroicon-m-calendar'),
                                        ])
                                ])
                        ])
                    
            ]);
    }
}