<?php

namespace App\Models;

use App\Enums\ServiceTier;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Service extends Model
{
    use HasFactory, HasSlug;

    protected $guarded = [];

    protected $casts = [
        'features' => 'array',
        'tier' => ServiceTier::class,
        'sort' => 'integer',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()->generateSlugsFrom('title')->saveSlugsTo('slug');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopeOrdered(Builder $q): Builder
    {
        return $q->orderBy('sort');
    }
}
