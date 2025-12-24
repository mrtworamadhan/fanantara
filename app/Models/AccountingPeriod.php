<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AccountingPeriod extends Model
{
    protected $guarded = [];
    protected $casts = ['start_date' => 'date', 'end_date' => 'date', 'is_closed' => 'boolean'];

    public function journalEntries(): HasMany
    {
        return $this->hasMany(JournalEntry::class);
    }
}