<?php

namespace App\Observers;

use App\Models\StockOpname;
use App\Models\InventoryStock;
use App\Models\StockMovement;
use App\Models\JournalEntry;
use App\Models\JournalItem;
use App\Models\Account;
use App\Models\AccountingPeriod;
use Illuminate\Support\Facades\DB;

class StockOpnameObserver
{
    public function created(StockOpname $opname): void
    {
        if ($opname->difference == 0) return;

        DB::transaction(function () use ($opname) {
            
            $stock = InventoryStock::where('product_id', $opname->product_id)
                ->where('warehouse_id', 1)
                ->first();

            if ($stock) {
                $stock->update(['quantity' => $opname->actual_qty]);

                StockMovement::create([
                    'inventory_stock_id' => $stock->id,
                    'user_id'            => auth()->id() ?? 1,
                    'type'               => $opname->difference < 0 ? 'out' : 'in', // Out kalau minus, In kalau plus
                    'quantity'           => abs($opname->difference),
                    'reference_number'   => $opname->opname_number,
                    'notes'              => 'Adjustment: ' . $opname->notes,
                ]);
            }

            $this->createJournal($opname);
        });
    }

    protected function createJournal(StockOpname $opname)
    {
        $period = AccountingPeriod::where('is_closed', false)->latest()->first();
        
        $hppValue = $opname->product->base_price * abs($opname->difference);

        $journal = JournalEntry::create([
            'accounting_period_id' => $period->id ?? 1,
            'transaction_date'     => now(),
            'reference_number'     => 'JV-' . $opname->opname_number,
            'description'          => 'Stock Opname: ' . $opname->product->name,
            'sourceable_id'        => $opname->id,
            'sourceable_type'      => StockOpname::class,
            'total_amount'         => $hppValue,
            'created_by'           => auth()->id() ?? 1,
        ]);

        
        $accPersediaan = Account::where('code', '1106')->firstOrFail()->id;
        
        if ($opname->difference < 0) {
            
            $accBeban = Account::where('code', '5200')->first()->id ?? Account::where('type', 'expense')->first()->id;

            JournalItem::create(['journal_entry_id' => $journal->id, 'account_id' => $accBeban,      'debit' => $hppValue, 'credit' => 0]);
            JournalItem::create(['journal_entry_id' => $journal->id, 'account_id' => $accPersediaan, 'debit' => 0,        'credit' => $hppValue]);

        } else {
            
            $accPendapatan = Account::where('code', '7100')->first()->id ?? Account::where('type', 'revenue')->first()->id;

            JournalItem::create(['journal_entry_id' => $journal->id, 'account_id' => $accPersediaan, 'debit' => $hppValue, 'credit' => 0]);
            JournalItem::create(['journal_entry_id' => $journal->id, 'account_id' => $accPendapatan, 'debit' => 0,        'credit' => $hppValue]);
        }
    }
}