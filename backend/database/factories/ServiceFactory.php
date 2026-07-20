<?php

namespace Database\Factories;

use App\Enums\ServiceTier;
use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ServiceFactory extends Factory
{
    protected $model = Service::class;

    public function definition(): array
    {
        $title = fake()->unique()->catchPhrase();
        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'summary' => fake()->sentence(12),
            'body' => '<p>' . implode('</p><p>', fake()->paragraphs(2)) . '</p>',
            'icon' => 'heroicon-o-' . fake()->word(),
            'tier' => fake()->randomElement(ServiceTier::cases()),
            'starting_price' => 'From $' . fake()->numberBetween(2, 12) . 'k',
            'features' => fake()->sentences(4),
            'sort' => fake()->numberBetween(0, 50),
        ];
    }
}
