<?php

namespace Database\Factories;

use App\Enums\SkillLevel;
use App\Models\Skill;
use Illuminate\Database\Eloquent\Factories\Factory;

class SkillFactory extends Factory
{
    protected $model = Skill::class;

    public function definition(): array
    {
        return [
            'name' => fake()->unique()->words(2, true),
            'icon' => 'simple-icons:' . fake()->word(),
            'category' => fake()->randomElement(['Languages', 'Frameworks', 'Styling', 'Data', 'Cloud', 'Tools']),
            'proficiency' => fake()->randomElement(SkillLevel::cases()),
            'years_experience' => fake()->numberBetween(1, 10),
            'color' => fake()->hexColor(),
            'sort' => fake()->numberBetween(0, 50),
        ];
    }

    public function level(SkillLevel $level): static
    {
        return $this->state(fn () => ['proficiency' => $level]);
    }
}
