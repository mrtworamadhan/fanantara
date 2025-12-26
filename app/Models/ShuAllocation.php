<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShuAllocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'percentage',
        'description',
        'is_active',
    ];

    protected $casts = [
        'percentage' => 'float',
        'is_active' => 'boolean',
    ];

    /**
     * Scope untuk mengambil hanya alokasi yang aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'account_id');
    }
}