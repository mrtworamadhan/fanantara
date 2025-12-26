<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MemberShuSnapshot extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'accounting_period_id',
        'accumulated_modal_weight',
        'total_transaction_volume',
        'last_updated_at',
    ];


    protected $casts = [
        'accumulated_modal_weight' => 'decimal:2',
        'total_transaction_volume' => 'decimal:2',
        'last_updated_at' => 'datetime',
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }


    public function period(): BelongsTo
    {
        return $this->belongsTo(AccountingPeriod::class, 'accounting_period_id');
    }
}