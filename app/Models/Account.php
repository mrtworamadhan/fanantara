<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Account extends Model
{
    protected $guarded = [];

    public function getCodeNameAttribute()
    {
        return $this->code . ' - ' . $this->name;
    }
    public function journalItems(): HasMany
    {
        return $this->hasMany(JournalItem::class);
    }
}