<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class InstitutionProfile extends Model
{
    protected $guarded = [];

    public function member(): MorphOne
    {
        return $this->morphOne(Member::class, 'profileable');
    }
}