<?php

namespace App\Livewire\Member;

use App\Models\SavingTransaction;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;

class Mutation extends Component
{
    public $filter = 'all'; 

    #[Layout('components.layouts.app')]

    public function render()
    {
        $member = Auth::user()->member;

        $query = SavingTransaction::query()
            ->with(['account.savingType'])
            ->whereHas('account', function ($q) use ($member) {
                $q->where('member_id', $member->id);
            });

        if ($this->filter !== 'all') {
            $query->where('type', $this->filter);
        }

        $transactions = $query->latest('transaction_date')
            ->latest('id')
            ->get()
            ->groupBy(function($item) {
                return \Carbon\Carbon::parse($item->transaction_date)->format('Y-m-d');
            });

        return view('livewire.member.mutation', [
            'groupedTransactions' => $transactions
        ]);
    }
}