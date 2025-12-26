<?php

namespace App\Observers;

use App\Models\SavingTransaction;
use App\Models\JournalEntry;
use App\Models\JournalItem;
use App\Models\Account;
use App\Models\AccountingPeriod;
use Illuminate\Support\Facades\DB;

class SavingTransactionObserver
{
    public function created(SavingTransaction $trx): void
    {
        $account = $trx->account;
        if ($trx->type === 'deposit') {
            $account->increment('balance', $trx->amount);
        } else {
            $account->decrement('balance', $trx->amount);
        }

        $this->createJournal($trx);
        $savingType = $trx->account->savingType;
        if ($savingType && $savingType->category === 'equity' && $trx->type === 'deposit') {
            $shuService = app(\App\Services\ShuService::class);
            $period = AccountingPeriod::where('is_closed', false)->latest()->first();

            if ($period) {
                $newWeight = $shuService->calculateWeight($trx->amount, $trx->transaction_date->format('Y-m-d'), $period);
                
                // Tambahkan bobot ke tabel snapshot
                \DB::table('member_shu_snapshots')
                    ->where('member_id', $trx->account->member_id)
                    ->where('accounting_period_id', $period->id)
                    ->increment('accumulated_modal_weight', $newWeight);
            }
        }
    }

    protected function createJournal(SavingTransaction $trx)
    {
        $savingType = $trx->savingType;

        if (! $savingType) {
            return; 
        }
        
        $accKas = Account::where('code', '1101')->first()->id;

        $codeMap = [
            'SP' => '3101',
            'SW' => '3102',
            'SS' => '2102',
        ];

        $targetCode = $codeMap[$savingType->code] ?? '2102';
        
        $accSimpanan = Account::where('code', $targetCode)->first()->id;

        $period = AccountingPeriod::where('is_closed', false)->latest()->first();

        DB::transaction(function () use ($trx, $accKas, $accSimpanan, $period, $savingType) {
            
            $journal = JournalEntry::create([
                'accounting_period_id' => $period->id ?? 1,
                'transaction_date'     => $trx->transaction_date,
                'reference_number'     => $trx->reference_number,
                'description'          => ucfirst($trx->type) . ' ' . $savingType->name . ' - ' . $trx->account->member->name,
                'sourceable_id'        => $trx->id,
                'sourceable_type'      => SavingTransaction::class,
                'total_amount'         => $trx->amount,
                'created_by'           => auth()->id() ?? 1,
            ]);

            if ($trx->type === 'deposit') {
                JournalItem::create(['journal_entry_id' => $journal->id, 'account_id' => $accKas, 'debit' => $trx->amount, 'credit' => 0]);
                JournalItem::create(['journal_entry_id' => $journal->id, 'account_id' => $accSimpanan, 'debit' => 0, 'credit' => $trx->amount]);
            } else {
                JournalItem::create(['journal_entry_id' => $journal->id, 'account_id' => $accSimpanan, 'debit' => $trx->amount, 'credit' => 0]);
                JournalItem::create(['journal_entry_id' => $journal->id, 'account_id' => $accKas, 'debit' => 0, 'credit' => $trx->amount]);
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
        
        JournalEntry::where('sourceable_id', $trx->id)->where('sourceable_type', SavingTransaction::class)->delete();
    }
}