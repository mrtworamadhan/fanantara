<?php

namespace App\Http\Controllers;

use App\Models\AccountingPeriod;
use App\Services\FinancialService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function downloadBundle(AccountingPeriod $period, FinancialService $service)
    {
        $data = [
            'period'         => $period,
            'logo_path'      => public_path('images/logo.png'),
            
            // 1. Laporan Utama
            'neraca'         => [
                'aset_lancar'  => $service->getAccountDetailsByRange('1100', '1199', $period),
                'aset_tetap'   => $service->getAccountDetailsByRange('1200', '1299', $period),
                'hutang'       => $service->getAccountDetailsByRange('2100', '2299', $period),
                'modal'        => $service->getAccountDetailsByRange('3000', '3999', $period),
                'shu_berjalan' => $service->getNetIncome($period),
            ],
            'phu'            => $service->getPhuData($period),
            'equity_changes' => $service->getEquityChanges($period),
            'arus_kas'       => $service->getCashFlowData($period),

            // 2. Lampiran Detail (Step 4.4)
            'buku_besar'     => $service->getGeneralLedger($period),
            'jurnal_umum'    => $service->getJournalHistory($period),

            //SHU
            'shu_members' => $service->getMemberShuReport($period),
            
            //ledger tabungan
            'member_ledger'   => $service->getMemberSubsidiaryLedger($period),
            
            'officials'       => $service->getOrganizationOfficials(),
        ];

        $pdf = Pdf::loadView('reports.finance-bundle', $data)
                ->setPaper('a4', 'portrait');
        return $pdf->stream("Bundel_Laporan_Keuangan_{$period->name}.pdf");

        // return $pdf->download("Bundel_Laporan_Keuangan_{$period->name}.pdf");
    }
}