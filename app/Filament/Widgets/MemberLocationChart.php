<?php

namespace App\Filament\Widgets;

use App\Models\Member;
use App\Models\Wilayah; // Pastikan Model Wilayah diimport
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class MemberLocationChart extends ChartWidget
{
    protected ?string $heading = 'Sebaran Anggota (Top 10 Provinsi)';
    
    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 'full'; 

    protected function getData(): array
    {
        // 1. Query Data (Sama kayak sebelumnya)
        $data = Member::query()
            ->select('province_code', DB::raw('count(*) as total'))
            ->whereNotNull('province_code')
            ->groupBy('province_code')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        $labels = [];
        $values = [];
        $colors = [];

        // 2. Siapkan Palet Warna (Hex Codes)
        // Ini urutan warna pelangi yang enak di mata (Merah, Orange, Kuning, Hijau, Biru, Ungu, Pink)
        $palette = [
            '#ef4444', // Red 500
            '#f97316', // Orange 500
            '#f59e0b', // Amber 500
            '#84cc16', // Lime 500
            '#10b981', // Emerald 500
            '#06b6d4', // Cyan 500
            '#3b82f6', // Blue 500
            '#6366f1', // Indigo 500
            '#8b5cf6', // Violet 500
            '#d946ef', // Fuchsia 500
            '#f43f5e', // Rose 500
        ];

        foreach ($data as $index => $row) {
            $namaProvinsi = Wilayah::where('kode', $row->province_code)
                ->value('nama') ?? 'Unknown';
            
            $labels[] = $namaProvinsi;
            $values[] = $row->total;
            
            // LOGIC WARNA WARNI:
            // Ambil warna berdasarkan urutan index.
            // Pakai Modulo (%) supaya kalau datanya lebih dari 11, warnanya ngulang dari awal lagi (gak error)
            $colors[] = $palette[$index % count($palette)];
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Anggota',
                    'data' => $values,
                    
                    // Masukkan Array Warna ke sini
                    'backgroundColor' => $colors, 
                    
                    // Border transparan biar bersih
                    'borderColor' => 'transparent', 
                    'borderWidth' => 0,
                    'borderRadius' => 4, // Sedikit melengkung biar modern
                ],
            ],
            'labels' => $labels,
        ];
    }
    protected function getPollingInterval(): ?string
    {
        return '15s';
    }

    protected function getType(): string
    {
        return 'bar'; 
    }
}