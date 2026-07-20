<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'rating' => 'integer',
        'featured' => 'boolean',
        'sort' => 'integer',
    ];

    public function scopeFeatured(Builder $q): Builder
    {
        return $q->where('featured', true);
    }

    public function scopeOrdered(Builder $q): Builder
    {
        return $q->orderBy('sort');
    }
}
