<?php

namespace App\Services;

use App\Models\AccountingPeriod;
use App\Models\ShuAllocation;
use App\Models\Member;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ShuService
{

    public function calculateModalWeight(float $amount, Carbon $transactionDate, AccountingPeriod $period): float
    {
        $endDate = Carbon::parse($period->end_date);
        
        $trxDate = $transactionDate->copy(); 

        if ($trxDate->lt($period->start_date)) {
            $trxDate = Carbon::parse($period->start_date);
        }

        $daysRemaining = $trxDate->diffInDays($endDate) + 1;
        return $amount * max(0, $daysRemaining);
    }


    public function getGlobalWeights(int $periodId): array
    {
        $totals = DB::table('member_shu_snapshots')
            ->where('accounting_period_id', $periodId)
            ->selectRaw('SUM(accumulated_modal_weight) as total_modal_weight, SUM(total_transaction_volume) as total_volume')
            ->first();

        $rawModal = $totals->total_modal_weight ?? 0;
        $rawVolume = $totals->total_volume ?? 0;

        $floatModal = (float) $rawModal;
        $floatVolume = (float) $rawVolume;

        return [
            'total_modal_weight' => $floatModal == 0 ? 1 : $floatModal,
            'total_volume'       => $floatVolume == 0 ? 1 : $floatVolume,
        ];
    }


    public function getEstimatedShu(int $memberId)
    {
        $period = AccountingPeriod::where('is_closed', false)->latest()->first();
        if (!$period) return 0;

        $netIncome = app(FinancialService::class)->getNetIncome($period);
        
        $allocations = ShuAllocation::whereIn('code', ['JM', 'JU'])->where('is_active', true)->get();
        $pModal = $allocations->where('code', 'JM')->first()?->percentage ?? 0;
        $pUsaha = $allocations->where('code', 'JU')->first()?->percentage ?? 0;

        $memberSnapshot = DB::table('member_shu_snapshots')
            ->where('member_id', $memberId)
            ->where('accounting_period_id', $period->id)
            ->first();
        
        if (!$memberSnapshot) return 0;

        $globals = $this->getGlobalWeights($period->id);

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
        return ShuAllocation::where('is_active', true)->get();
    }


    public function updateSnapshot($memberId, $periodId, array $data)
    {
        return DB::table('member_shu_snapshots')->updateOrInsert(
            ['member_id' => $memberId, 'accounting_period_id' => $periodId],
            array_merge($data, [
                'last_updated_at' => now(), 
                'updated_at' => now()
            ])
        );
    }
}