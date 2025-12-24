<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $guarded = [];

    public function payable(): MorphTo
    {
        return $this->morphTo();
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }
}