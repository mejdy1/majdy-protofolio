<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'available_for_hire' => 'boolean',
        'roles' => 'array',
    ];

    public static function current(): self
    {
        return static::query()->firstOrCreate(
            ['id' => 1],
            [
                'name' => 'Your Name',
                'headline' => 'Designer & Engineer',
                'tagline' => 'Crafting products people love.',
                'bio' => 'Edit this profile in the admin panel.',
                'location' => 'Earth',
                'email' => 'hello@portfolio.test',
                'available_for_hire' => true,
                'roles' => ['Designer', 'Engineer', 'Maker'],
            ]
        );
    }
}
