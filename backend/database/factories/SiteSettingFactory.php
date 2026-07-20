<?php

namespace Database\Factories;

use App\Models\SiteSetting;
use Illuminate\Database\Eloquent\Factories\Factory;

class SiteSettingFactory extends Factory
{
    protected $model = SiteSetting::class;

    public function definition(): array
    {
        return [
            'key' => 'misc.' . fake()->unique()->word(),
            'value' => fake()->sentence(3),
            'group' => 'general',
            'label' => fake()->words(2, true),
            'type' => 'text',
        ];
    }
}
