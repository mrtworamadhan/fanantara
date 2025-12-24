<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SavingAccount extends Model
{
    protected $guarded = [];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function savingType(): BelongsTo
    {
        return $this->belongsTo(SavingType::class, 'saving_type_id');
    }
    
    public function transactions(): HasMany
    {
        return $this->hasMany(SavingTransaction::class);
    }
}