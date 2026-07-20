<?php

namespace Database\Factories;

use App\Enums\ProjectStatus;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProjectFactory extends Factory
{
    protected $model = Project::class;

    public function definition(): array
    {
        $title = fake()->unique()->sentence(3);
        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'summary' => fake()->sentence(14),
            'body' => "<p>" . implode("</p><p>", fake()->paragraphs(4)) . "</p>",
            'cover_url' => 'https://picsum.photos/seed/' . Str::slug($title) . '/1600/900',
            'gallery' => [
                'https://picsum.photos/seed/' . Str::random(6) . '/1600/900',
                'https://picsum.photos/seed/' . Str::random(6) . '/1600/900',
            ],
            'stack' => fake()->randomElements(['Laravel','Vue','React','Next.js','TypeScript','Tailwind','Postgres','GraphQL','Redis','AWS'], 5),
            'live_url' => fake()->url(),
            'repo_url' => 'https://github.com/example/' . Str::slug($title),
            'client' => fake()->company(),
            'role' => fake()->randomElement(['Lead engineer','Designer + Engineer','Tech lead','Full-stack engineer']),
            'year' => fake()->numberBetween(2021, 2026),
            'featured' => fake()->boolean(40),
            'status' => ProjectStatus::Published,
            'sort' => fake()->numberBetween(0, 100),
        ];
    }
}
