<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use JsonException;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'type'];

    protected static string $cacheKey = 'settings.all';

    protected static function booted(): void
    {
        static::saved(static fn () => self::clearCache());
        static::deleted(static fn () => self::clearCache());
    }

    /**
     * Get all settings as key => value array.
     */
    public static function allSettings(): array
    {
        return self::cached();
    }

    /**
     * Get a single setting value by key.
     */
    public static function get(string $key, $default = null)
    {
        $settings = self::cached(); // reuse the same cache

        $setting = $settings[$key] ?? null;

        return $setting ?? $default;
    }

    /**
     * Set or update a setting.
     *
     * @throws JsonException
     */
    public static function set(string $key, $value, string $type = 'string'): void
    {
        $stored = $type === 'json' ? json_encode($value, JSON_THROW_ON_ERROR) : (string) $value;

        static::query()->updateOrCreate(['key' => $key], ['value' => $stored, 'type' => $type]);

        self::clearCache();
    }

    /**
     * Remove a setting and refresh the cache.
     */
    public static function forget(string $key): void
    {
        static::query()->where('key', $key)->delete();
        self::clearCache();
    }

    /**
     * Clear the settings cache.
     */
    public static function clearCache(): void
    {
        Cache::forget(self::$cacheKey);
    }

    /**
     * Shared cache source for both get() and allSettings()
     */
    protected static function cached(): array
    {
        return Cache::rememberForever(self::$cacheKey, static fn () => static::all()
            ->keyBy('key')
            ->map(fn ($setting) => self::cast($setting->value, $setting->type))
            ->toArray());
    }

    /**
     * Cast a value to its expected type.
     *
     * @throws JsonException
     */
    protected static function cast($value, string $type)
    {
        return match ($type) {
            'int', 'integer' => (int) $value,
            'bool', 'boolean' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            'float' => (float) $value,
            'json', 'array' => json_decode((string) $value, true, 512, JSON_THROW_ON_ERROR),
            default => $value,
        };
    }
}
