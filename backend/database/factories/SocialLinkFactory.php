<?php

namespace Database\Factories;

use App\Models\SocialLink;
use Illuminate\Database\Eloquent\Factories\Factory;

class SocialLinkFactory extends Factory
{
    protected $model = SocialLink::class;

    public function definition(): array
    {
        $platform = fake()->unique()->randomElement(['GitHub', 'X', 'LinkedIn', 'Dribbble', 'Read.cv', 'Mastodon', 'YouTube']);
        return [
            'platform' => $platform,
            'url' => 'https://example.com/' . fake()->userName(),
            'handle' => '@' . fake()->userName(),
            'icon' => 'simple-icons:' . strtolower($platform),
            'sort' => fake()->numberBetween(0, 50),
        ];
    }
}
