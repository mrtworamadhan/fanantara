<?php

namespace App\Console\Commands;

use App\Models\Member;
use App\Models\AccountingPeriod;
use App\Services\ShuService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class InitializeMemberShuWeight extends Command
{
    protected $signature = 'shu:init-weight';

    protected $description = 'Menghitung bobot awal SHU anggota berdasarkan saldo carry-over awal tahun';

    public function handle(ShuService $shuService)
    {
        $period = AccountingPeriod::where('is_closed', false)->latest()->first();

        if (!$period) {
            $this->error('Tidak ada periode akuntansi yang aktif!');
            return;
        }

        $this->info("Memulai inisialisasi bobot SHU untuk periode: {$period->name}");

        $members = Member::with(['savingAccounts' => function ($query) {
            $query->whereHas('savingType', function ($q) {
                $q->where('category', 'equity');
            });
        }])->get();

        $bar = $this->output->createProgressBar($members->count());
        $bar->start();

        DB::transaction(function () use ($members, $period, $shuService, $bar) {
            foreach ($members as $member) {
                $totalBalance = $member->savingAccounts->sum('balance');

                if ($totalBalance > 0) {
                    $initialWeight = $shuService->calculateModalWeight(
                        $totalBalance, 
                        $period->start_date, 
                        $period
                    );

                    $shuService->updateSnapshot($member->id, $period->id, [
                        'accumulated_modal_weight' => $initialWeight,
                        'total_transaction_volume' => 0,
                    ]);
                }
                $bar->advance();
            }
        });

        $bar->finish();
        $this->newLine();
        $this->info('Inisialisasi selesai! Seluruh bobot awal anggota telah tercatat.');
    }
}