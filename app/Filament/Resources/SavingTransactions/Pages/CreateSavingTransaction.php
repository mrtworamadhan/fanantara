<?php

namespace App\Filament\Resources\SavingTransactions\Pages;

use App\Filament\Resources\SavingTransactions\SavingTransactionResource;
use App\Models\SavingAccount;
use App\Models\SavingType;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateSavingTransaction extends CreateRecord
{
    protected static string $resource = SavingTransactionResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $memberId = $data['member_id'];
        $typeId   = $data['saving_type_id'];

        $account = SavingAccount::firstOrCreate(
            [
                'member_id' => $memberId,
                'saving_type_id' => $typeId
            ],
            [
                'account_number' => SavingType::find($typeId)->code . '-' . str_pad($memberId, 4, '0', STR_PAD_LEFT) . '-' . date('y'),
                'balance' => 0
            ]
        );

        $savingType = $account->type;

        if ($data['type'] === 'withdrawal' && !$savingType->is_withdrawable) {
             Notification::make()->title('Gagal')->body('Simpanan Pokok/Wajib tidak boleh ditarik kecuali keluar anggota!')->danger()->send();
             $this->halt();
        }

        if ($data['type'] === 'withdrawal' && $account->balance < $data['amount']) {
             Notification::make()->title('Gagal')->body('Saldo tidak mencukupi! Sisa: Rp ' . number_format($account->balance))->danger()->send();
             $this->halt();
        }

        $data['saving_account_id'] = $account->id;
        $data['reference_number'] = 'TRX-' . time();
        $data['created_by'] = auth()->id();

        unset($data['member_id']);
        unset($data['saving_type_id']);

        return $data;
    }
}
