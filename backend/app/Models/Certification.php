<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certification extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'issue_date' => 'date',
        'expiry_date' => 'date',
        'featured' => 'boolean',
        'sort' => 'integer',
    ];

    public function scopeOrdered(Builder $q): Builder
    {
        return $q->orderBy('sort')->orderByDesc('issue_date');
    }

    public function scopeFeatured(Builder $q): Builder
    {
        return $q->where('featured', true);
    }
}
