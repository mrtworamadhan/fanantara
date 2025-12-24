<?php

namespace App\Observers;

use App\Models\Purchase;
use App\Models\JournalEntry;
use App\Models\JournalItem;
use App\Models\Account;
use App\Models\AccountingPeriod;
use Illuminate\Support\Facades\DB;

class PurchaseObserver
{
    public function updated(Purchase $purchase): void
    {
        if ($purchase->isDirty('status') && $purchase->status === 'received') {
            
            if (JournalEntry::where('sourceable_id', $purchase->id)->where('sourceable_type', Purchase::class)->exists()) {
                return;
            }

            $this->createJournal($purchase);
        }
    }

    protected function createJournal(Purchase $purchase)
    {
        $accPersediaan = Account::where('code', '1106')->first()->id; 

        if ($purchase->payment_status === 'paid') {
            $accKredit = Account::where('code', '1101')->first()->id; // Kas di Koperasi
            $keterangan = ' (Lunas Tunai)';
        } else {
            $accKredit = Account::where('code', '2101')->first()->id; // Hutang Usaha
            $keterangan = ' (Hutang/Tempo)';
        }

        $period = AccountingPeriod::where('is_closed', false)->latest()->first();

        DB::transaction(function () use ($purchase, $accPersediaan, $accKredit, $period, $keterangan) {
            
            // Header Jurnal
            $journal = JournalEntry::create([
                'accounting_period_id' => $period->id ?? 1, 
                'transaction_date'     => now(),
                'reference_number'     => 'JV-' . $purchase->purchase_number,
                'description'          => 'Penerimaan Barang PO: ' . $purchase->purchase_number . $keterangan,
                'sourceable_id'        => $purchase->id,
                'sourceable_type'      => Purchase::class,
                'total_amount'         => $purchase->total_amount,
                'created_by'           => auth()->id() ?? 1,
            ]);

            // Detail Jurnal (Debit: Persediaan)
            JournalItem::create([
                'journal_entry_id' => $journal->id,
                'account_id'       => $accPersediaan,
                'debit'            => $purchase->total_amount,
                'credit'           => 0,
            ]);

            // Detail Jurnal (Kredit: Kas atau Hutang)
            JournalItem::create([
                'journal_entry_id' => $journal->id,
                'account_id'       => $accKredit, // <--- DINAMIS
                'debit'            => 0,
                'credit'           => $purchase->total_amount,
            ]);
        });
    }

    public function deleted(Purchase $purchase): void
    {
        // 1. HAPUS JURNAL
        $journal = JournalEntry::where('sourceable_id', $purchase->id)
            ->where('sourceable_type', Purchase::class)
            ->first();

        if ($journal) {
            $journal->delete();
        }

        // 2. TARIK BALIK STOK (ROLLBACK)
        if ($purchase->status === 'received') {
            foreach ($purchase->items as $item) {
                $stock = \App\Models\InventoryStock::where('warehouse_id', $purchase->warehouse_id)
                    ->where('product_id', $item->product_id)
                    ->first();
                
                if ($stock) {
                    if ($stock->quantity >= $item->quantity) {
                        $stock->decrement('quantity', $item->quantity);
                    } else {
                        $stock->update(['quantity' => 0]); 
                    }

                    \App\Models\StockMovement::create([
                        'inventory_stock_id' => $stock->id,
                        'user_id'            => auth()->id() ?? 1,
                        'type'               => 'adjustment',
                        'quantity'           => -($item->quantity),
                        'reference_number'   => 'DEL-' . $purchase->purchase_number,
                        'notes'              => 'Rollback: PO dihapus dari database',
                    ]);
                }
            }
        }
    }
}