<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $guarded = [];

    /**
     * Get setting value by key
     */
    public static function get(string $key, $default = null): ?string
    {
        static $cache = [];

        if (!isset($cache[$key])) {
            $setting = static::where('key', $key)->first();
            $cache[$key] = $setting?->value;
        }

        return $cache[$key] ?? $default;
    }
}