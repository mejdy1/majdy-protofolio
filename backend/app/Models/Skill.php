<?php

namespace App\Models;

use App\Enums\SkillLevel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'proficiency' => SkillLevel::class,
        'years_experience' => 'integer',
        'sort' => 'integer',
    ];

    public function scopeOrdered(Builder $q): Builder
    {
        return $q->orderBy('sort')->orderBy('category')->orderBy('name');
    }
}
