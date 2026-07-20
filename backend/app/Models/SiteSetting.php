<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSetting extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'value' => 'array',
    ];

    public static function get(string $key, mixed $default = null): mixed
    {
        return Cache::rememberForever("setting:{$key}", function () use ($key, $default) {
            $row = static::query()->where('key', $key)->first();
            return $row?->value ?? $default;
        });
    }

    public static function put(string $key, mixed $value, string $group = 'general', string $type = 'text'): self
    {
        Cache::forget("setting:{$key}");
        return static::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'group' => $group, 'type' => $type]
        );
    }

    protected static function booted(): void
    {
        static::saved(fn (self $s) => Cache::forget("setting:{$s->key}"));
        static::deleted(fn (self $s) => Cache::forget("setting:{$s->key}"));
    }
}
