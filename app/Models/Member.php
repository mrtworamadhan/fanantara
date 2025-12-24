<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Member extends Model
{
    protected $guarded = []; 

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function profileable(): MorphTo
    {
        return $this->morphTo();
    }

    public function individualProfile(): BelongsTo
    {
        return $this->belongsTo(IndividualProfile::class, 'profileable_id');
    }

    public function institutionProfile(): BelongsTo
    {
        return $this->belongsTo(InstitutionProfile::class, 'profileable_id');
    }


    public function isInstitution(): bool
    {
        return $this->type === 'institution';
    }

    public function getNameAttribute(): string
    {
        if (! $this->profileable) {
            return '-';
        }

        return match ($this->type) {
            'individual' => $this->profileable->full_name ?? '-',
            'institution' => $this->profileable->company_name ?? '-',
            default => '-',
        };
    }

    public function getIdentityNumberAttribute(): string
    {
        if (! $this->profileable) return '-';

        return match ($this->type) {
            'individual' => $this->profileable->nik ?? '-', // Pastikan di table individual_profiles nama kolomnya 'nik'
            'institution' => $this->profileable->nib ?? $this->profileable->sku_number ?? '-', // Pastikan nama kolomnya benar
            default => '-',
        };
    }

    public function getPhoneAttribute(): string
    {
        if (! $this->profileable) return '-';

        return $this->profileable->phone ?? $this->profileable->phone_number ?? '-';
    }

    public function getAddressAttribute(): string
    {
        if (! $this->profileable) return '-';
        
        return $this->profileable->address ?? '-';
    }

    public function getImageUrlAttribute(): ?string
    {
        if (! $this->profileable) return null;

        $path = match ($this->type) {
            'individual' => $this->profileable->photo ?? $this->profileable->picture, 
            'institution' => $this->profileable->logo,
            default => null,
        };

        if (is_array($path)) {
            return $path[0] ?? null;
        }

        return $path;
    }

    public function savingAccounts(): HasMany
    {
        return $this->hasMany(SavingAccount::class);
    }

    public function savingTransactions()
    {
        return $this->hasMany(SavingTransaction::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function getEquityBalance()
    {
        return $this->savingAccounts()
            ->whereHas('savingType', function ($query) {
                $query->where('category', 'equity'); // Hanya Ekuitas
            })
            ->sum('balance');
    }

    public function province()
    {
        return $this->belongsTo(Wilayah::class, 'province_code', 'kode');
    }

    public function city()
    {
        return $this->belongsTo(Wilayah::class, 'city_code', 'kode');
    }

    public function district()
    {
        return $this->belongsTo(Wilayah::class, 'district_code', 'kode');
    }

    public function village()
    {
        return $this->belongsTo(Wilayah::class, 'village_code', 'kode');
    }

    public function getFullAddressAttribute()
    {
        return "{$this->street_address}, " . 
               ($this->village->nama ?? '') . ", " . 
               ($this->district->nama ?? '') . ", " . 
               ($this->city->nama ?? '') . ", " . 
               ($this->province->nama ?? '');
    }
}