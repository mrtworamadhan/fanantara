<?php

namespace App\Services;

use App\Models\AccountingPeriod;
use App\Models\ShuAllocation;
use App\Models\Member;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ShuService
{
    /**
     * Alias dari calculateWeight sesuai request
     */
    public function calculateModalWeight(float $amount, string $transactionDate, AccountingPeriod $period): float
    {
        $endDate = Carbon::parse($period->end_date);
        $trxDate = Carbon::parse($transactionDate);

        if ($trxDate->lt($period->start_date)) {
            $trxDate = Carbon::parse($period->start_date);
        }

        $daysRemaining = $trxDate->diffInDays($endDate) + 1;
        return $amount * max(0, $daysRemaining);
    }

    /**
     * Mengambil pembagi (denominator) global dari tabel snapshot
     */
    public function getGlobalWeights(int $periodId): array
    {
        $totals = DB::table('member_shu_snapshots')
            ->where('accounting_period_id', $periodId)
            ->selectRaw('SUM(accumulated_modal_weight) as total_modal_weight, SUM(total_transaction_volume) as total_volume')
            ->first();

        return [
            'total_modal_weight' => (float) ($totals->total_modal_weight ?? 1),
            'total_volume'       => (float) ($totals->total_volume ?? 1),
        ];
    }

    /**
     * Menghitung estimasi SHU Member secara real-time untuk Dashboard
     */
    public function getEstimatedShu(int $memberId)
    {
        $period = AccountingPeriod::where('is_closed', false)->latest()->first();
        if (!$period) return 0;

        // 1. Ambil Laba Berjalan dari FinancialService
        $netIncome = app(FinancialService::class)->getNetIncome($period->start_date, $period->end_date);
        
        // 2. Ambil Persentase Alokasi (JM & JU)
        $allocations = ShuAllocation::whereIn('code', ['JM', 'JU'])->active()->get();
        $pModal = $allocations->where('code', 'JM')->first()?->percentage ?? 0;
        $pUsaha = $allocations->where('code', 'JU')->first()?->percentage ?? 0;

        // 3. Ambil Snapshot Member & Global
        $memberSnapshot = DB::table('member_shu_snapshots')
            ->where('member_id', $memberId)
            ->where('accounting_period_id', $period->id)
            ->first();
        
        if (!$memberSnapshot) return 0;

        $globals = $this->getGlobalWeights($period->id);

        // 4. Hitung Estimasi Rupiah
        $estModal = ($memberSnapshot->accumulated_modal_weight / $globals['total_modal_weight']) * ($netIncome * ($pModal / 100));
        $estUsaha = ($memberSnapshot->total_transaction_volume / $globals['total_volume']) * ($netIncome * ($pUsaha / 100));

        return [
            'total_estimation' => round($estModal + $estUsaha),
            'breakdown' => [
                'jasa_modal' => round($estModal),
                'jasa_usaha' => round($estUsaha),
            ],
            'last_update' => $memberSnapshot->last_updated_at
        ];
    }
    public function getActiveAllocations()
    {
        return ShuAllocation::active()->get();
    }

    public function updateSnapshot($memberId, $periodId, array $data)
    {
        return DB::table('member_shu_snapshots')->updateOrInsert(
            ['member_id' => $memberId, 'accounting_period_id' => $periodId],
            array_merge($data, ['last_updated_at' => now(), 'updated_at' => now()])
        );
    }
}