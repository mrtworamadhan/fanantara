<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\JournalEntry;
use App\Models\JournalItem;
use App\Models\Account;
use App\Models\AccountingPeriod;
use Illuminate\Support\Facades\DB;

class OrderObserver
{
    public function updated(Order $order): void
    {
        if ($order->isDirty('status') && $order->status === 'completed') {
            
            if (JournalEntry::where('sourceable_id', $order->id)->where('sourceable_type', Order::class)->exists()) {
                return;
            }

            $this->createJournal($order);
        }
    }

    protected function createJournal(Order $order)
    {
        $isMember = in_array($order->member->type, ['individual', 'institution']);
        
        // Akun Kredit: PENDAPATAN
        $accPendapatan = Account::where('code', $isMember ? '4101' : '4201')->first()->id;

        // --- B. DETEKSI CARA BAYAR  ---
        // Cek status pembayaran dari form Order
        if ($order->payment_status === 'paid') {
            $accDebitPenjualan = Account::where('code', '1101')->first()->id; 
            $descTambahan = '(Lunas Tunai)';
        } else {
            $accDebitPenjualan = Account::where('code', $isMember ? '1103' : '1104')->first()->id;
            $descTambahan = '(Tempo/Piutang)';
        }

        // Akun HPP & Persediaan
        $accHPP        = Account::where('code', '5100')->first()->id; // [cite: 52]
        $accPersediaan = Account::where('code', '1106')->first()->id; // [cite: 16]

        // --- C. HITUNG TOTAL HPP ---
        $totalHPP = 0;
        foreach ($order->items as $item) {
            $totalHPP += ($item->product->base_price * $item->quantity);
        }

        $period = AccountingPeriod::where('is_closed', false)->latest()->first();

        DB::transaction(function () use ($order, $accDebitPenjualan, $accPendapatan, $accHPP, $accPersediaan, $totalHPP, $period, $descTambahan) {
            
            // 1. Buat Header Jurnal
            $journal = JournalEntry::create([
                'accounting_period_id' => $period->id ?? 1,
                'transaction_date'     => now(),
                'reference_number'     => 'JV-' . $order->order_number,
                'description'          => 'Penjualan Order: ' . $order->order_number . ' ' . $descTambahan,
                'sourceable_id'        => $order->id,
                'sourceable_type'      => Order::class,
                'total_amount'         => $order->total_amount,
                'created_by'           => auth()->id() ?? 1,
            ]);

            // --- JURNAL 1: PENJUALAN (Harga Jual) ---
            // Debit: KAS (jika paid) atau PIUTANG (jika unpaid)
            JournalItem::create([
                'journal_entry_id' => $journal->id,
                'account_id'       => $accDebitPenjualan,
                'debit'            => $order->total_amount,
                'credit'           => 0,
            ]);
            // Kredit: PENDAPATAN
            JournalItem::create([
                'journal_entry_id' => $journal->id,
                'account_id'       => $accPendapatan,
                'debit'            => 0,
                'credit'           => $order->total_amount,
            ]);

            // --- JURNAL 2: HPP (Harga Modal) ---
            // Debit: BEBAN POKOK (HPP)
            JournalItem::create([
                'journal_entry_id' => $journal->id,
                'account_id'       => $accHPP,
                'debit'            => $totalHPP,
                'credit'           => 0,
            ]);
            // Kredit: PERSEDIAAN
            JournalItem::create([
                'journal_entry_id' => $journal->id,
                'account_id'       => $accPersediaan,
                'debit'            => 0,
                'credit'           => $totalHPP,
            ]);
        });
    }

    public function deleted(Order $order): void
    {
        $journal = JournalEntry::where('sourceable_id', $order->id)
            ->where('sourceable_type', Order::class)
            ->first();

        if ($journal) {
            $journal->delete(); // Item jurnal otomatis kehapus karena cascadeOnDelete di migration
        }

        if ($order->status === 'completed') {
            foreach ($order->items as $item) {
                $stock = \App\Models\InventoryStock::where('warehouse_id', $order->warehouse_id)
                    ->where('product_id', $item->product_id)
                    ->first();
                
                if ($stock) {
                    $stock->increment('quantity', $item->quantity);

                    \App\Models\StockMovement::create([
                        'inventory_stock_id' => $stock->id,
                        'user_id'            => auth()->id() ?? 1,
                        'type'               => 'adjustment',
                        'quantity'           => $item->quantity,
                        'reference_number'   => 'DEL-' . $order->order_number,
                        'notes'              => 'Rollback: Order dihapus dari database',
                    ]);
                }
            }
        }
    }
}