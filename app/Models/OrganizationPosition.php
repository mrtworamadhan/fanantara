<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrganizationPosition extends Model
{
    protected $guarded = [];

    public function structures()
    {
        return $this->hasMany(OrganizationStructure::class);
    }
}