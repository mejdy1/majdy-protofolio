<?php

namespace Database\Factories;

use App\Models\Experience;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExperienceFactory extends Factory
{
    protected $model = Experience::class;

    public function definition(): array
    {
        $start = fake()->dateTimeBetween('-6 years', '-1 year');
        return [
            'role' => fake()->jobTitle(),
            'company' => fake()->company(),
            'company_url' => fake()->url(),
            'location' => fake()->city(),
            'start_date' => $start->format('Y-m-d'),
            'end_date' => fake()->dateTimeBetween($start, 'now')->format('Y-m-d'),
            'current' => false,
            'description' => fake()->paragraph(),
            'achievements' => fake()->sentences(3),
            'stack' => fake()->randomElements(['Laravel', 'Vue', 'React', 'TypeScript', 'Postgres', 'Redis', 'AWS'], 3),
            'sort' => fake()->numberBetween(0, 50),
        ];
    }

    public function current(): static
    {
        return $this->state(fn () => ['current' => true, 'end_date' => null]);
    }
}
