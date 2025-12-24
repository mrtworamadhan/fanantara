<?php

namespace App\Observers;

use App\Models\Payment;
use App\Models\Order;
use App\Models\Purchase;
use App\Models\JournalEntry;
use App\Models\JournalItem;
use App\Models\Account;
use App\Models\AccountingPeriod;
use Illuminate\Support\Facades\DB;

class PaymentObserver
{
    public function created(Payment $payment): void
    {
        $this->createJournal($payment);
        $this->updateStatus($payment);
    }

    protected function createJournal(Payment $payment)
    {
        $period = AccountingPeriod::where('is_closed', false)->latest()->first();
        
        DB::transaction(function () use ($payment, $period) {
            
            // Header Jurnal
            $journal = JournalEntry::create([
                'accounting_period_id' => $period->id ?? 1,
                'transaction_date'     => $payment->payment_date,
                'reference_number'     => 'PAY-' . $payment->id,
                'description'          => 'Pelunasan ' . class_basename($payment->payable_type) . ' #' . $payment->payable_id,
                'sourceable_id'        => $payment->id,
                'sourceable_type'      => Payment::class,
                'total_amount'         => $payment->amount,
                'created_by'           => auth()->id() ?? 1,
            ]);

            // LOGIC JURNAL
            if ($payment->payable_type === Purchase::class) {
                // KASUS BAYAR HUTANG KE SUPPLIER (Purchase)
                // Debit: Hutang Usaha (2101)
                // Kredit: Kas/Bank (Sesuai input user)
                
                $accHutang = Account::where('code', '2101')->first()->id;

                JournalItem::create(['journal_entry_id' => $journal->id, 'account_id' => $accHutang, 'debit' => $payment->amount, 'credit' => 0]);
                JournalItem::create(['journal_entry_id' => $journal->id, 'account_id' => $payment->account_id, 'debit' => 0, 'credit' => $payment->amount]);

            } elseif ($payment->payable_type === Order::class) {
                // KASUS TERIMA PIUTANG DARI MEMBER (Order)
                // Debit: Kas/Bank (Sesuai input user)
                // Kredit: Piutang Usaha (1103/1104)

                // Cek tipe member untuk akun Piutang yg pas
                $isMember = in_array($payment->payable->member->type, ['individual', 'institution']);
                $accPiutang = Account::where('code', $isMember ? '1103' : '1104')->first()->id;

                JournalItem::create(['journal_entry_id' => $journal->id, 'account_id' => $payment->account_id, 'debit' => $payment->amount, 'credit' => 0]);
                JournalItem::create(['journal_entry_id' => $journal->id, 'account_id' => $accPiutang, 'debit' => 0, 'credit' => $payment->amount]);
            }
        });
    }

    protected function updateStatus(Payment $payment)
    {
        // Cek apakah sudah lunas total?
        $transaction = $payment->payable;
        $totalPaid = $transaction->payments()->sum('amount');

        if ($totalPaid >= $transaction->total_amount) {
            $transaction->update(['payment_status' => 'paid']);
        }
    }
}