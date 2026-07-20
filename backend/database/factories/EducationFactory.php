<?php

namespace Database\Factories;

use App\Models\Education;
use Illuminate\Database\Eloquent\Factories\Factory;

class EducationFactory extends Factory
{
    protected $model = Education::class;

    public function definition(): array
    {
        $start = fake()->dateTimeBetween('-12 years', '-5 years');
        return [
            'school' => fake()->company() . ' University',
            'degree' => fake()->randomElement(['B.Sc.', 'M.Sc.', 'B.A.']),
            'field' => fake()->randomElement(['Computer Science', 'Software Engineering', 'Design', 'Mathematics']),
            'start_date' => $start->format('Y-m-d'),
            'end_date' => fake()->dateTimeBetween($start, '-1 year')->format('Y-m-d'),
            'description' => fake()->sentence(10),
            'sort' => fake()->numberBetween(0, 50),
        ];
    }
}
