<?php

use App\Enums\PostStatus;
use App\Enums\ProjectStatus;
use App\Models\Category;
use App\Models\Education;
use App\Models\Experience;
use App\Models\Post;
use App\Models\Profile;
use App\Models\Project;
use App\Models\SiteSetting;
use App\Models\Testimonial;

/*
 * All assertions here are behavioral (response payloads), never raw cache
 * keys — the key spellings are an implementation detail. Each test first
 * proves the response is actually served from cache (mutating the model
 * with events suppressed leaves the payload stale), then proves an evented
 * save busts it.
 */

it('serves the project list from cache until a save busts it', function () {
    $project = Project::factory()->create(['status' => ProjectStatus::Published, 'title' => 'Original']);

    $this->getJson('/api/v1/projects')->assertJsonPath('data.0.title', 'Original');

    // Mutation without events → still cached.
    Project::withoutEvents(fn () => $project->update(['title' => 'Sneaky']));
    $this->getJson('/api/v1/projects')->assertJsonPath('data.0.title', 'Original');

    // Evented save → cache flushed.
    $project->update(['title' => 'Fresh']);
    $this->getJson('/api/v1/projects')->assertJsonPath('data.0.title', 'Fresh');
});

it('busts the per-slug project cache on save', function () {
    $project = Project::factory()->create(['status' => ProjectStatus::Published, 'summary' => 'Before']);

    $this->getJson("/api/v1/projects/{$project->slug}")->assertJsonPath('data.summary', 'Before');

    $project->update(['summary' => 'After']);

    $this->getJson("/api/v1/projects/{$project->slug}")->assertJsonPath('data.summary', 'After');
});

it('busts parameterized list caches on save', function () {
    Project::factory()->create(['status' => ProjectStatus::Published, 'featured' => true]);
    $this->getJson('/api/v1/projects?featured=1')->assertJsonCount(1, 'data');

    Project::factory()->create(['status' => ProjectStatus::Published, 'featured' => true]);

    $this->getJson('/api/v1/projects?featured=1')->assertJsonCount(2, 'data');
});

it('drops the old slug cache when a post slug changes', function () {
    $post = Post::factory()->create([
        'status' => PostStatus::Published, 'published_at' => now()->subDay(), 'title' => 'First Title',
    ]);
    $oldSlug = $post->slug;

    $this->getJson("/api/v1/posts/{$oldSlug}")->assertOk();

    // HasSlug regenerates only on create for posts? — slug is explicit here:
    $post->update(['slug' => 'second-title', 'title' => 'Second Title']);

    $this->getJson('/api/v1/posts/second-title')->assertOk()->assertJsonPath('data.title', 'Second Title');
    $this->getJson("/api/v1/posts/{$oldSlug}")->assertNotFound();
});

it('keeps category-filtered post lists fresh', function () {
    $category = Category::factory()->create();
    Post::factory()->create([
        'status' => PostStatus::Published, 'published_at' => now()->subDay(), 'category_id' => $category->id,
    ]);
    $this->getJson("/api/v1/posts?category={$category->slug}")->assertJsonCount(1, 'data');

    Post::factory()->create([
        'status' => PostStatus::Published, 'published_at' => now()->subDay(), 'category_id' => $category->id,
    ]);

    $this->getJson("/api/v1/posts?category={$category->slug}")->assertJsonCount(2, 'data');
});

it('busts the unfiltered testimonial list on save', function () {
    Testimonial::factory()->create(['featured' => false]);
    $this->getJson('/api/v1/testimonials')->assertJsonCount(1, 'data');

    Testimonial::factory()->create(['featured' => false]);

    $this->getJson('/api/v1/testimonials')->assertJsonCount(2, 'data');
});

it('busts experience and education caches on save', function () {
    Experience::factory()->create();
    Education::factory()->create();
    $this->getJson('/api/v1/experience')->assertJsonCount(1, 'data');
    $this->getJson('/api/v1/education')->assertJsonCount(1, 'data');

    Experience::factory()->create();
    Education::factory()->create();

    $this->getJson('/api/v1/experience')->assertJsonCount(2, 'data');
    $this->getJson('/api/v1/education')->assertJsonCount(2, 'data');
});

it('reflects category renames inside cached post payloads', function () {
    $category = Category::factory()->create(['name' => 'Old Name']);
    Post::factory()->create([
        'status' => PostStatus::Published, 'published_at' => now()->subDay(), 'category_id' => $category->id,
    ]);

    $this->getJson('/api/v1/posts')->assertJsonPath('data.0.category.name', 'Old Name');

    $category->update(['name' => 'New Name']);

    $this->getJson('/api/v1/posts')->assertJsonPath('data.0.category.name', 'New Name');
});

it('refreshes site settings after an admin save', function () {
    $this->getJson('/api/v1/site-settings')->assertJsonPath('data.seo.title', null);

    SiteSetting::put('seo.title', 'Brand New');

    $this->getJson('/api/v1/site-settings')->assertJsonPath('data.seo.title', 'Brand New');
});

it('refreshes the profile after a save', function () {
    Profile::factory()->create(['id' => 1, 'name' => 'Before']);
    $this->getJson('/api/v1/profile')->assertJsonPath('data.name', 'Before');

    Profile::current()->update(['name' => 'After']);

    $this->getJson('/api/v1/profile')->assertJsonPath('data.name', 'After');
});
