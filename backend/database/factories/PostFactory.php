<?php

namespace Database\Factories;

use App\Enums\PostStatus;
use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition(): array
    {
        $title = fake()->unique()->sentence(5);
        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'excerpt' => fake()->sentence(20),
            'body' => "<p>" . implode("</p><p>", fake()->paragraphs(8)) . "</p>",
            'cover_url' => 'https://picsum.photos/seed/' . Str::slug($title) . '/1600/900',
            'reading_time' => fake()->numberBetween(3, 12),
            'published_at' => fake()->dateTimeBetween('-1 year', 'now'),
            'status' => PostStatus::Published,
            'featured' => fake()->boolean(30),
        ];
    }
}
