<?php

namespace App\Observers;

use App\Models\SavingTransaction;
use App\Models\JournalEntry;
use App\Models\JournalItem;
use App\Models\Account;
use App\Models\AccountingPeriod;
use App\Services\ShuService;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SavingTransactionObserver
{
    public function created(SavingTransaction $trx): void
    {
        $account = $trx->account;
        
        // 1. Update Saldo Akun Simpanan
        if ($trx->type === 'deposit') {
            $account->increment('balance', $trx->amount);
        } else {
            $account->decrement('balance', $trx->amount);
        }

        $this->createJournal($trx);

        $savingType = $account->savingType;
        if ($savingType && $savingType->category === 'equity' && $trx->type === 'deposit') {
            $shuService = app(ShuService::class);
            
            $period = AccountingPeriod::where('is_closed', false)->latest()->first();

            if ($period) {
                $newWeight = $shuService->calculateModalWeight(
                    (float) $trx->amount, 
                    $trx->transaction_date,
                    $period
                );
                
                $shuService->updateSnapshot($account->member_id, $period->id, [
                    'accumulated_modal_weight' => DB::raw("accumulated_modal_weight + $newWeight")
                ]);
            }
        }
    }

    protected function createJournal(SavingTransaction $trx)
    {
        $savingType = $trx->account->savingType;
        if (!$savingType) return;

        $accKas = Account::where('code', '1101')->first()?->id;
        $codeMap = ['SP' => '3101', 'SW' => '3102', 'SS' => '2102'];
        $targetCode = $codeMap[$savingType->code] ?? '2102';
        $accSimpanan = Account::where('code', $targetCode)->first()?->id;

        $period = AccountingPeriod::where('is_closed', false)->latest()->first();

        if (!$accKas || !$accSimpanan || !$period) return;

        DB::transaction(function () use ($trx, $accKas, $accSimpanan, $period, $savingType) {
            $journal = JournalEntry::create([
                'accounting_period_id' => $period->id, // Menggunakan ID dari Periode Aktif
                'transaction_date'     => $trx->transaction_date,
                'reference_number'     => $trx->reference_number,
                'description'          => ucfirst($trx->type) . ' ' . $savingType->name . ' - ' . $trx->account->member->name,
                'sourceable_id'        => $trx->id,
                'sourceable_type'      => SavingTransaction::class,
                'total_amount'         => $trx->amount,
                'created_by'           => auth()->id() ?? 1,
            ]);

            if ($trx->type === 'deposit') {
                $journal->items()->create(['account_id' => $accKas, 'debit' => $trx->amount, 'credit' => 0]);
                $journal->items()->create(['account_id' => $accSimpanan, 'debit' => 0, 'credit' => $trx->amount]);
            } else {
                $journal->items()->create(['account_id' => $accSimpanan, 'debit' => $trx->amount, 'credit' => 0]);
                $journal->items()->create(['account_id' => $accKas, 'debit' => 0, 'credit' => $trx->amount]);
            }
        });
    }

    public function deleted(SavingTransaction $trx): void
    {
        $account = $trx->account;
        if ($trx->type === 'deposit') {
            $account->decrement('balance', $trx->amount);
        } else {
            $account->increment('balance', $trx->amount);
        }
        
        JournalEntry::where('sourceable_id', $trx->id)
            ->where('sourceable_type', SavingTransaction::class)
            ->delete();
    }
}