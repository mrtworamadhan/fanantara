<?php

namespace App\Services;

use App\Models\Account;
use App\Models\AccountingPeriod;
use App\Models\JournalEntry;
use App\Models\JournalItem;
use App\Models\ShuAllocation;
use App\Models\ShuDistribution;
use Illuminate\Support\Facades\DB;

class FinancialService
{

    public function getBalanceByRange($startCode, $endCode, AccountingPeriod $period): float
    {
        $accounts = Account::whereBetween('code', [$startCode, $endCode])->get();
        return $accounts->sum(fn($account) => $this->calculateAccountBalance($account, $period));
    }

    public function getAccountDetailsByRange($startCode, $endCode, AccountingPeriod $period): array
    {
        return Account::whereBetween('code', [$startCode, $endCode])
            ->get()
            ->map(fn($account) => [
                'code_name' => "{$account->code} - {$account->name}",
                'balance' => $this->calculateAccountBalance($account, $period),
            ])
            ->filter(fn($item) => $item['balance'] != 0)
            ->values()
            ->toArray();
    }

    private function calculateAccountBalance($account, AccountingPeriod $period)
    {
        $res = DB::table('journal_items')
            ->join('journal_entries', 'journal_items.journal_entry_id', '=', 'journal_entries.id')
            ->where('journal_items.account_id', $account->id)
            ->where('journal_entries.accounting_period_id', $period->id) 
            ->selectRaw('SUM(debit) as total_debit, SUM(credit) as total_credit')
            ->first();

        $debit = $res->total_debit ?? 0;
        $credit = $res->total_credit ?? 0;

        if (in_array($account->type, ['asset', 'expense'])) {
            return $debit - $credit;
        }
        return $credit - $debit;
    }

    public function getNetIncome(AccountingPeriod $period): float
    {
        $accounts = Account::whereIn('type', ['revenue', 'expense'])->get();
        $totalRevenue = 0;
        $totalExpenses = 0;

        foreach ($accounts as $account) {
            $balance = DB::table('journal_items')
                ->join('journal_entries', 'journal_items.journal_entry_id', '=', 'journal_entries.id')
                ->where('journal_items.account_id', $account->id)
                ->where('journal_entries.accounting_period_id', $period->id) 
                ->selectRaw('SUM(credit) - SUM(debit) as balance')
                ->first()->balance ?? 0;

            if ($account->type === 'revenue') {
                $totalRevenue += $balance;
            } else {
                $totalExpenses += abs($balance); 
            }
        }
        return $totalRevenue - $totalExpenses;
    }

    public function getEquityChanges(AccountingPeriod $period): array
    {
        $accounts = Account::where('type', 'equity')->orderBy('code')->get();
        
        return $accounts->map(function ($account) use ($period) {
            $initial = DB::table('journal_items')
                ->join('journal_entries', 'journal_items.journal_entry_id', '=', 'journal_entries.id')
                ->where('journal_items.account_id', $account->id)
                ->where('journal_entries.transaction_date', '<', $period->start_date)
                ->selectRaw('SUM(credit) - SUM(debit) as balance')
                ->first()->balance ?? 0;

            $currentData = DB::table('journal_items')
                ->join('journal_entries', 'journal_items.journal_entry_id', '=', 'journal_entries.id')
                ->where('journal_items.account_id', $account->id)
                ->where('journal_entries.accounting_period_id', $period->id)
                ->selectRaw('SUM(credit) as addition, SUM(debit) as reduction')
                ->first();

            $endingBalance = $initial + ($currentData->addition ?? 0) - ($currentData->reduction ?? 0);

            return [
                'account_name' => "{$account->code} - {$account->name}",
                'initial_balance' => $initial,
                'addition' => $currentData->addition ?? 0,
                'reduction' => $currentData->reduction ?? 0,
                'ending_balance' => $endingBalance,
            ];
        })->toArray();
    }

    public function getShuAllocations(): array
    {
        return ShuAllocation::where('is_active', true)
            ->pluck('percentage', 'code')
            ->toArray();
    }
    public function getCashFlowData(AccountingPeriod $period): array
    {
        $initialCash = DB::table('journal_items')
            ->join('journal_entries', 'journal_items.journal_entry_id', '=', 'journal_entries.id')
            ->join('accounts', 'journal_items.account_id', '=', 'accounts.id')
            ->whereBetween('accounts.code', ['1101', '1102'])
            ->where('journal_entries.transaction_date', '<', $period->start_date)
            ->selectRaw('SUM(debit) - SUM(credit) as balance')
            ->first()->balance ?? 0;

        $netIncome = $this->getNetIncome($period);
        
        $deltaAssetLancar = $this->calculateDelta('1103', '1107', $period) * -1;
        $deltaLiabilitas = $this->calculateDelta('2101', '2104', $period);

        $totalOperating = $netIncome + $deltaAssetLancar + $deltaLiabilitas;
        $totalInvesting = $this->calculateDelta('1201', '1201', $period) * -1;
        $totalFinancing = $this->calculateDelta('3101', '3201', $period);

        return [
            'initial_cash' => $initialCash,
            'final_cash' => $initialCash + $totalOperating + $totalInvesting + $totalFinancing,
            'operating' => ['net_income' => $netIncome, 'total' => $totalOperating],
            'investing' => ['total' => $totalInvesting],
            'financing' => ['total' => $totalFinancing],
            'net_increase' => $totalOperating + $totalInvesting + $totalFinancing,
        ];
    }

    private function calculateDelta($startCode, $endCode, AccountingPeriod $period): float
    {
        return $this->getBalanceByRange($startCode, $endCode, $period);
    }

    public function getPhuData(AccountingPeriod $period): array
    {
        $revenues = $this->getAccountDetailsByRange('4000', '4999', $period);
        $totalRevenue = collect($revenues)->sum('balance');

        $expenses = $this->getAccountDetailsByRange('5000', '5999', $period);
        $totalExpense = collect($expenses)->sum('balance');

        return [
            'revenue_list' => $revenues,
            'total_revenue' => $totalRevenue,
            'expense_list' => $expenses,
            'total_expense' => $totalExpense,
            'net_shu' => $totalRevenue - $totalExpense,
        ];
    }
    
    public function getJournalHistory(AccountingPeriod $period)
    {
        return JournalEntry::with(['items.account'])
            ->where('accounting_period_id', $period->id)
            ->orderBy('transaction_date')
            ->orderBy('reference_number')
            ->get();
    }

    public function getGeneralLedger(AccountingPeriod $period)
    {
        return Account::with(['journalItems' => function ($query) use ($period) {
            $query->join('journal_entries', 'journal_items.journal_entry_id', '=', 'journal_entries.id')
                  ->where('journal_entries.accounting_period_id', $period->id)
                  ->orderBy('journal_entries.transaction_date');
        }])
        ->get()
        ->map(function ($account) use ($period) {
            $openingBalance = DB::table('journal_items')
                ->join('journal_entries', 'journal_items.journal_entry_id', '=', 'journal_entries.id')
                ->where('journal_items.account_id', $account->id)
                ->where('journal_entries.transaction_date', '<', $period->start_date)
                ->selectRaw('SUM(debit) as d, SUM(credit) as c')
                ->first();

            $initial = in_array($account->type, ['asset', 'expense']) 
                ? ($openingBalance->d - $openingBalance->c) 
                : ($openingBalance->c - $openingBalance->d);

            return [
                'account_info' => "{$account->code} - {$account->name}",
                'initial_balance' => $initial,
                'items' => $account->journalItems,
            ];
        })->filter(fn($acc) => count($acc['items']) > 0 || $acc['initial_balance'] != 0);
    }

    public function getMemberShuReport(AccountingPeriod $period)
    {
        return ShuDistribution::with(['details.member'])
            ->where('accounting_period_id', $period->id)
            ->where('status', 'completed')
            ->first();
    }

    public function getMemberSubsidiaryLedger(AccountingPeriod $period)
    {
        return \App\Models\Member::with(['savingAccounts.savingType'])->get()->map(function ($member) use ($period) {
            $accountsData = $member->savingAccounts->map(function ($account) use ($period) {
                $initialBalance = \App\Models\SavingTransaction::where('saving_account_id', $account->id)
                    ->where('transaction_date', '<', $period->start_date)
                    ->selectRaw("SUM(CASE WHEN type = 'deposit' THEN amount ELSE -amount END) as balance")
                    ->first()->balance ?? 0;

                $mutations = \App\Models\SavingTransaction::where('saving_account_id', $account->id)
                    ->whereBetween('transaction_date', [$period->start_date, $period->end_date])
                    ->orderBy('transaction_date')
                    ->get();

                $totalMutation = $mutations->sum(fn($m) => $m->type === 'deposit' ? $m->amount : -$m->amount);

                return [
                    'account_name' => $account->savingType->name,
                    'initial_balance' => $initialBalance,
                    'mutations' => $mutations,
                    'final_balance' => $initialBalance + $totalMutation,
                ];
            });

            return [
                'name' => $member->name,
                'accounts' => $accountsData
            ];
        });
    }

    public function getOrganizationOfficials()
    {
        $positions = [
            'chairman' => 'Ketua',
            'treasurer' => 'Bendahara',
            'supervisor' => 'Pengawas',
        ];

        $results = [];

        foreach ($positions as $key => $name) {
            $results[$key] = \App\Models\Member::whereHas('structures', function ($query) use ($name) {
                $query->where('is_active', true)
                      ->whereHas('position', function ($q) use ($name) {
                          $q->where('name', 'like', '%' . $name . '%');
                      });
            })->first()?->name ?? '____________________';
        }

        return $results;
    }


    
}