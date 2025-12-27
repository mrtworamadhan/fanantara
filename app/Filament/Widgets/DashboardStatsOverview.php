<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\Member;
use App\Models\InventoryStock;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class DashboardStatsOverview extends BaseWidget
{
    protected static ?int $sort = 1; // Taruh paling atas
    protected ?string $pollingInterval = '15s'; // Auto refresh tiap 15 detik

    protected function getStats(): array
    {
        // 1. DATA OMSET BULAN INI
        $omset = Order::where('status', 'completed')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_amount');

        // Sparkline (Grafik kecil di kartu)
        $omsetTrend = Trend::model(Order::class)
            ->between(start: now()->startOfMonth(), end: now()->endOfMonth())
            ->perDay()
            ->sum('total_amount');

        // 2. STOK KRITIS (Gudang Utama)
        $lowStock = InventoryStock::where('warehouse_id', 1)
            ->where('quantity', '<=', 5) // Anggap 5 itu kritis
            ->count();

        // 3. MEMBER BARU BULAN INI
        $newMembers = Member::whereMonth('created_at', now()->month)->count();

        return [
            Stat::make('Omset Bulan Ini', 'Rp ' . number_format($omset, 0, ',', '.'))
                ->description('Total Penjualan Valid')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success')
                ->chart($omsetTrend->map(fn (TrendValue $value) => $value->aggregate)->toArray()),

            Stat::make('Order Selesai', Order::where('status', 'completed')->count())
                ->description('Total Transaksi')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('primary'),

            Stat::make('Stok Menipis', $lowStock . ' Produk')
                ->description('Perlu Restock Segera')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color($lowStock > 0 ? 'danger' : 'success'),
            
            Stat::make('Anggota Aktif', Member::count())
                ->description('+' . $newMembers . ' bulan ini')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('info'),
        ];
    }
    protected function getPollingInterval(): ?string
    {
        return '15s';
    }
}