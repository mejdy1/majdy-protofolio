<?php

namespace Database\Factories;

use App\Models\Profile;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProfileFactory extends Factory
{
    protected $model = Profile::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'headline' => fake()->jobTitle(),
            'tagline' => fake()->sentence(8),
            'bio' => '<p>' . implode('</p><p>', fake()->paragraphs(2)) . '</p>',
            'location' => fake()->city() . ', ' . fake()->country(),
            'email' => fake()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'avatar_url' => 'https://i.pravatar.cc/300?img=' . fake()->numberBetween(1, 70),
            'hero_image_url' => 'https://picsum.photos/seed/' . fake()->word() . '/1600/900',
            'resume_url' => fake()->url(),
            'available_for_hire' => true,
            'roles' => fake()->words(3),
        ];
    }
}
