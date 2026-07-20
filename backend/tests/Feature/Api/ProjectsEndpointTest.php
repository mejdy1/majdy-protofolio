<?php

use App\Enums\ProjectStatus;
use App\Models\Project;

it('lists only published projects', function () {
    $live = Project::factory()->create(['status' => ProjectStatus::Published]);
    Project::factory()->create(['status' => ProjectStatus::Draft]);
    Project::factory()->create(['status' => ProjectStatus::Archived]);
    Project::factory()->create(['status' => ProjectStatus::Published])->delete();

    $this->getJson('/api/v1/projects')
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.id', $live->id);
});

it('orders by sort asc, then year desc, then id desc', function () {
    $third = Project::factory()->create(['sort' => 1, 'year' => 2026, 'status' => ProjectStatus::Published]);
    $second = Project::factory()->create(['sort' => 0, 'year' => 2023, 'status' => ProjectStatus::Published]);
    $first = Project::factory()->create(['sort' => 0, 'year' => 2025, 'status' => ProjectStatus::Published]);

    $ids = collect($this->getJson('/api/v1/projects')->json('data'))->pluck('id')->all();

    expect($ids)->toBe([$first->id, $second->id, $third->id]);
});

it('excludes body and gallery from the list shape', function () {
    Project::factory()->create(['status' => ProjectStatus::Published]);

    $this->getJson('/api/v1/projects')
        ->assertOk()
        ->assertJsonMissingPath('data.0.body')
        ->assertJsonMissingPath('data.0.gallery')
        ->assertJsonStructure(['data' => [[
            'id', 'title', 'slug', 'summary', 'cover_url', 'stack', 'year',
            'featured', 'client', 'role', 'live_url', 'repo_url',
        ]]]);
});

it('filters to featured projects with ?featured=1', function () {
    $starred = Project::factory()->create(['featured' => true, 'status' => ProjectStatus::Published]);
    Project::factory()->create(['featured' => false, 'status' => ProjectStatus::Published]);

    $this->getJson('/api/v1/projects?featured=1')
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.id', $starred->id);
});

it('limits the list with ?limit', function () {
    Project::factory()->count(4)->create(['status' => ProjectStatus::Published]);

    $this->getJson('/api/v1/projects?limit=2')->assertOk()->assertJsonCount(2, 'data');
});

it('includes body and gallery with ?full=1', function () {
    Project::factory()->create(['status' => ProjectStatus::Published]);

    $response = $this->getJson('/api/v1/projects?full=1')->assertOk();

    expect($response->json('data.0.body'))->not->toBeNull()
        ->and($response->json('data.0.gallery'))->toBeArray();
});

it('returns body and gallery on the detail endpoint', function () {
    $project = Project::factory()->create([
        'status' => ProjectStatus::Published,
        'body' => '<p>Case study</p>',
        'gallery' => ['https://example.com/a.jpg'],
    ]);

    $this->getJson("/api/v1/projects/{$project->slug}")
        ->assertOk()
        ->assertJsonPath('data.body', '<p>Case study</p>')
        ->assertJsonPath('data.gallery', ['https://example.com/a.jpg']);
});

it('defaults stack and gallery to empty arrays', function () {
    $project = Project::factory()->create(['status' => ProjectStatus::Published, 'stack' => null, 'gallery' => null]);

    $this->getJson("/api/v1/projects/{$project->slug}")
        ->assertOk()
        ->assertJsonPath('data.stack', [])
        ->assertJsonPath('data.gallery', []);
});

it('404s as JSON for unpublished or unknown slugs', function (callable $makeSlug) {
    $slug = $makeSlug();

    $this->getJson("/api/v1/projects/{$slug}")
        ->assertNotFound()
        ->assertJsonStructure(['message']);
})->with([
    'draft' => fn () => Project::factory()->create(['status' => ProjectStatus::Draft])->slug,
    'archived' => fn () => Project::factory()->create(['status' => ProjectStatus::Archived])->slug,
    'soft-deleted' => function () {
        $p = Project::factory()->create(['status' => ProjectStatus::Published]);
        $p->delete();
        return $p->slug;
    },
    'unknown' => fn () => 'does-not-exist',
]);
