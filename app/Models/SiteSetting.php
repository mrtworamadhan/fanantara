<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    // Only allow these fields to be mass-assigned (prevents value_file, value_text, etc. from being saved)
    protected $fillable = [
        'key',
        'value',
        'type',
        'label',
    ];

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