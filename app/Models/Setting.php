<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'description'
    ];

    // Cache settings untuk performance
    protected static $cache = [];

    /**
     * Get setting value by key
     */
    public static function get($key, $default = null)
    {
        // Check cache first
        if (isset(self::$cache[$key])) {
            return self::$cache[$key];
        }

        $setting = self::where('key', $key)->first();
        $value = $setting ? $setting->value : $default;

        // Store in cache
        self::$cache[$key] = $value;

        return $value;
    }

    /**
     * Set setting value
     */
    public static function set($key, $value)
    {
        $setting = self::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );

        // Update cache
        self::$cache[$key] = $value;

        return $setting;
    }

    /**
     * Get all settings by group
     */
    public static function getByGroup($group)
    {
        return self::where('group', $group)->get();
    }

    /**
     * Clear cache
     */
    public static function clearCache()
    {
        self::$cache = [];
    }
}
