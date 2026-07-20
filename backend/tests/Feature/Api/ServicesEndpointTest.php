<?php

use App\Enums\ServiceTier;
use App\Models\Service;

it('lists services ordered by sort', function () {
    $second = Service::factory()->create(['sort' => 2]);
    $first = Service::factory()->create(['sort' => 1, 'tier' => ServiceTier::Premium, 'features' => null]);

    $response = $this->getJson('/api/v1/services')->assertOk();

    expect(collect($response->json('data'))->pluck('id')->all())->toBe([$first->id, $second->id]);

    $response->assertJsonPath('data.0.tier', 'premium')
        ->assertJsonPath('data.0.features', [])
        ->assertJsonStructure(['data' => [[
            'id', 'title', 'slug', 'summary', 'icon', 'tier', 'starting_price', 'features',
        ]]]);
});

it('returns a single service by slug', function () {
    $service = Service::factory()->create();

    $this->getJson("/api/v1/services/{$service->slug}")
        ->assertOk()
        ->assertJsonPath('data.id', $service->id)
        ->assertJsonPath('data.title', $service->title);
});

it('404s as JSON for unknown slugs', function () {
    $this->getJson('/api/v1/services/nope')
        ->assertNotFound()
        ->assertJsonStructure(['message']);
});
