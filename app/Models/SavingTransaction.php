<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SavingTransaction extends Model
{
    protected $guarded = [];

    protected $casts = [
        'transaction_date' => 'date',
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(SavingAccount::class, 'saving_account_id');
    }

    public function member() { 
        return $this->belongsTo(Member::class);
     }

    public function savingType() { 
        return $this->belongsTo(SavingType::class, 'type_id');
     }
}