<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShuDistribution extends Model
{
    protected $guarded = [];

    public function period() { return $this->belongsTo(AccountingPeriod::class, 'accounting_period_id'); }
    
    public function details() { return $this->hasMany(ShuDistributionDetail::class); }
}