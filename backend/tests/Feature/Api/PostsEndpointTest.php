<?php

use App\Enums\PostStatus;
use App\Models\Category;
use App\Models\Post;

it('lists only published posts with past publish dates', function () {
    $visible = Post::factory()->create(['status' => PostStatus::Published, 'published_at' => now()->subDay()]);
    Post::factory()->create(['status' => PostStatus::Draft, 'published_at' => now()->subDay()]);
    Post::factory()->create(['status' => PostStatus::Scheduled, 'published_at' => now()->subDay()]);
    Post::factory()->create(['status' => PostStatus::Published, 'published_at' => now()->addWeek()]);
    Post::factory()->create(['status' => PostStatus::Published, 'published_at' => null]);
    Post::factory()->create(['status' => PostStatus::Published, 'published_at' => now()->subDay()])->delete();

    $this->getJson('/api/v1/posts')
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.id', $visible->id);
});

it('orders by published_at descending and shapes rows with nested category', function () {
    $category = Category::factory()->create(['name' => 'Engineering', 'color' => '#112233']);
    $older = Post::factory()->create([
        'status' => PostStatus::Published, 'published_at' => now()->subDays(5), 'category_id' => $category->id,
    ]);
    $newer = Post::factory()->create([
        'status' => PostStatus::Published, 'published_at' => now()->subDay(), 'category_id' => $category->id,
    ]);

    $response = $this->getJson('/api/v1/posts')->assertOk();

    expect(collect($response->json('data'))->pluck('id')->all())->toBe([$newer->id, $older->id]);

    $response->assertJsonPath('data.0.category.name', 'Engineering')
        ->assertJsonPath('data.0.category.slug', $category->slug)
        ->assertJsonPath('data.0.category.color', '#112233')
        ->assertJsonMissingPath('data.0.body')
        ->assertJsonStructure(['data' => [[
            'id', 'title', 'slug', 'excerpt', 'cover_url', 'reading_time', 'published_at',
        ]]]);

    // ISO-8601 timestamp
    expect($response->json('data.0.published_at'))->toMatch('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}/');
});

it('paginates nine posts per page with meta', function () {
    Post::factory()->count(12)->create(['status' => PostStatus::Published, 'published_at' => now()->subDay()]);

    $this->getJson('/api/v1/posts')
        ->assertOk()
        ->assertJsonCount(9, 'data')
        ->assertJsonPath('meta.current_page', 1)
        ->assertJsonPath('meta.last_page', 2)
        ->assertJsonPath('meta.total', 12);

    $this->getJson('/api/v1/posts?page=2')
        ->assertOk()
        ->assertJsonCount(3, 'data')
        ->assertJsonPath('meta.current_page', 2);
});

it('filters by category slug', function () {
    $engineering = Category::factory()->create();
    $design = Category::factory()->create();
    $match = Post::factory()->create([
        'status' => PostStatus::Published, 'published_at' => now()->subDay(), 'category_id' => $engineering->id,
    ]);
    Post::factory()->create([
        'status' => PostStatus::Published, 'published_at' => now()->subDay(), 'category_id' => $design->id,
    ]);

    $this->getJson("/api/v1/posts?category={$engineering->slug}")
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.id', $match->id);

    $this->getJson('/api/v1/posts?category=no-such-category')
        ->assertOk()
        ->assertJsonCount(0, 'data');
});

it('returns the body on the detail endpoint', function () {
    $post = Post::factory()->create([
        'status' => PostStatus::Published,
        'published_at' => now()->subDay(),
        'body' => '<p>Full article body</p>',
    ]);

    $this->getJson("/api/v1/posts/{$post->slug}")
        ->assertOk()
        ->assertJsonPath('data.body', '<p>Full article body</p>');
});

it('404s as JSON for invisible or unknown slugs', function (callable $makeSlug) {
    $slug = $makeSlug();

    $this->getJson("/api/v1/posts/{$slug}")
        ->assertNotFound()
        ->assertJsonStructure(['message']);
})->with([
    'draft' => fn () => Post::factory()->create(['status' => PostStatus::Draft])->slug,
    'scheduled future' => fn () => Post::factory()->create([
        'status' => PostStatus::Published, 'published_at' => now()->addDay(),
    ])->slug,
    'soft-deleted' => function () {
        $p = Post::factory()->create(['status' => PostStatus::Published, 'published_at' => now()->subDay()]);
        $p->delete();
        return $p->slug;
    },
    'unknown' => fn () => 'does-not-exist',
]);
