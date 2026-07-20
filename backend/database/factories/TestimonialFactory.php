<?php

namespace Database\Factories;

use App\Models\Testimonial;
use Illuminate\Database\Eloquent\Factories\Factory;

class TestimonialFactory extends Factory
{
    protected $model = Testimonial::class;

    public function definition(): array
    {
        return [
            'author' => fake()->name(),
            'role' => fake()->jobTitle(),
            'company' => fake()->company(),
            'avatar_url' => 'https://i.pravatar.cc/160?img=' . fake()->numberBetween(1, 70),
            'quote' => fake()->paragraph(),
            'rating' => fake()->numberBetween(1, 5),
            'featured' => fake()->boolean(30),
            'sort' => fake()->numberBetween(0, 50),
        ];
    }

    public function featured(): static
    {
        return $this->state(fn () => ['featured' => true]);
    }
}
