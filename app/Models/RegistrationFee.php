<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RegistrationFee extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'member_type', 
        'amount', 
        'is_active',   
    ];


    protected $casts = [
        'amount' => 'decimal:2', 
        'is_active' => 'boolean', 
    ];


    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}