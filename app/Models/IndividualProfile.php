<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class IndividualProfile extends Model
{
    protected $guarded = [];

    protected $casts = [
        'production_profile' => 'array',
        'consumption_profile' => 'array',
    ];


    public function member(): MorphOne
    {
        return $this->morphOne(Member::class, 'profileable');
    }

    protected function photo(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? [$value] : [],
            set: fn ($value) => is_array($value) ? ($value[0] ?? null) : $value,
        );
    }
    
    protected function ktpImage(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? [$value] : [],
            set: fn ($value) => is_array($value) ? ($value[0] ?? null) : $value,
        );
    }

    protected function npwpImage(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? [$value] : [],
            set: fn ($value) => is_array($value) ? ($value[0] ?? null) : $value,
        );
    }
}