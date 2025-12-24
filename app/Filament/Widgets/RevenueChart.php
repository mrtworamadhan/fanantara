<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Order;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class RevenueChart extends ChartWidget
{
    protected ?string $heading = 'Grafik Penjualan Harian';
    protected static ?int $sort = 2; // Taruh di bawah kartu stats
    protected int | string | array $columnSpan = 'full'; // Lebar full
    protected ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $data = Trend::model(Order::class)
            ->between(
                start: now()->subDays(30),
                end: now(),
            )
            ->perDay()
            ->sum('total_amount');

        return [
            'datasets' => [
                [
                    'label' => 'Penjualan (Rp)',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                    'fill' => true, 
                    'borderColor' => '#3b82f6', 
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'tension' => 0.5,
                    'pointRadius' => 3,
                    'pointHoverRadius' => 6,
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => \Carbon\Carbon::parse($value->date)->format('d M')),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    // Opsi tambahan biar grafik bersih (Opsional)
    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
            'scales' => [
                'y' => [
                    'ticks' => [
                        'callback' => "function(value) { return 'Rp ' + new Intl.NumberFormat('id-ID').format(value); }", // Format Rupiah di Sumbu Y
                    ],
                    'grid' => [
                        'display' => true,
                        'drawBorder' => false,
                        'color' => 'rgba(0,0,0,0.05)',
                    ],
                ],
                'x' => [
                    'grid' => [
                        'display' => false,
                    ],
                ],
            ],
        ];
    }
}