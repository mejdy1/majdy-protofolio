<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialLink extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'sort' => 'integer',
    ];

    public function scopeOrdered(Builder $q): Builder
    {
        return $q->orderBy('sort');
    }
}
