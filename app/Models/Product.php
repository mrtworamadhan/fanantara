<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $guarded = [];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'supplier_id');
    }

    public function inventoryStocks(): HasMany
    {
        return $this->hasMany(InventoryStock::class);
    }

    public function getStockAttribute()
    {
        if (isset($this->attributes['total_stock'])) {
            return $this->attributes['total_stock'];
        }
        
        return $this->inventoryStocks()->sum('quantity');
    }
}