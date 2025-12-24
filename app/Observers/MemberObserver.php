<?php

namespace App\Observers;

use App\Models\Member;
use App\Models\SavingAccount;
use App\Models\SavingType;

class MemberObserver
{
    public function created(Member $member): void
    {
        $types = SavingType::whereIn('code', ['SP', 'SW'])->get();

        foreach ($types as $type) {
            SavingAccount::create([
                'member_id' => $member->id,
                'saving_type_id' => $type->id,
                'account_number' => $type->code . '-' . str_pad($member->id, 4, '0', STR_PAD_LEFT) . '-' . date('y'),
                'balance' => 0,
            ]);
        }
    }
}