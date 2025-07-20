<?php

namespace App\Services;

use App\Models\Setting;
use JsonException;

class SettingsManager
{
    protected string $cacheKey = 'settings.cache';

    /**
     * Get a setting by key with type casting.
     */
    public function get(string $key, $default = null): mixed
    {
        return Setting::get($key, $default);
    }

    /**
     * @throws JsonException
     */
    public function set(string $key, mixed $value, string $type = 'string'): void
    {
        Setting::set($key, $value, $type);
    }

    public function forget(string $key): void
    {
        Setting::forget($key);
    }

    public function all(): array
    {
        return Setting::allSettings();
    }
}
