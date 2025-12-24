<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrganizationStructure extends Model
{
    protected $guarded = [];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function position()
    {
        return $this->belongsTo(OrganizationPosition::class, 'organization_position_id');
    }
}