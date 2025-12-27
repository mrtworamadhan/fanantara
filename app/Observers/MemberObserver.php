<?php

namespace App\Observers;

use App\Mail\WelcomeMemberMail;
use App\Models\Member;
use App\Models\SavingAccount;
use App\Models\SavingType;
use App\Models\AccountingPeriod;
use App\Models\MemberShuSnapshot;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class MemberObserver
{
    public function created(Member $member): void
    {
        if (empty($member->member_number)) {
            $member->update([
                'member_number' => 'MBR-' . date('Ym') . '-' . str_pad($member->id, 4, '0', STR_PAD_LEFT)
            ]);
        }
        
        $types = SavingType::whereIn('code', ['SP', 'SW', 'SS'])->get();
        foreach ($types as $type) {
            SavingAccount::create([
                'member_id' => $member->id,
                'saving_type_id' => $type->id,
                'account_number' => $type->code . '-' . str_pad($member->id, 4, '0', STR_PAD_LEFT) . '-' . date('y'),
                'balance' => 0,
            ]);
        }

        $activePeriod = AccountingPeriod::where('is_closed', false)->latest()->first();
        if ($activePeriod) {
            MemberShuSnapshot::create([
                'member_id' => $member->id,
                'accounting_period_id' => $activePeriod->id,
                'accumulated_modal_weight' => 0,
                'total_transaction_volume' => 0,
                'last_updated_at' => now(),
            ]);
        }

        if ($member->user && $member->user->email) {
            $member->load('savingAccounts.savingType');
            
            try {
                Mail::to($member->user->email)->send(new WelcomeMemberMail($member));
            } catch (\Exception $e) {
                Log::error("Gagal kirim email: " . $e->getMessage());
            }
        }
    }
}