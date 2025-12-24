<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class JournalEntry extends Model
{
    protected $guarded = [];

    public function items(): HasMany
    {
        return $this->hasMany(JournalItem::class);
    }

    public function sourceable(): MorphTo
    {
        return $this->morphTo();
    }
    
    public function period(): BelongsTo
    {
        return $this->belongsTo(AccountingPeriod::class, 'accounting_period_id');
    }
}