<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wilayah extends Model
{
    protected $table = 'wilayah'; 
    protected $primaryKey = 'kode';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;


    public function scopeProvinsi($query)
    {
        return $query->whereRaw('CHAR_LENGTH(kode) = 2');
    }

    public function scopeKota($query)
    {
        return $query->whereRaw('CHAR_LENGTH(kode) = 5');
    }

    public function scopeKecamatan($query)
    {
        return $query->whereRaw('CHAR_LENGTH(kode) = 8');
    }

    public function scopeDesa($query)
    {
        return $query->whereRaw('CHAR_LENGTH(kode) = 13');
    }
}