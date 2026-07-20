<?php

use App\Models\Testimonial;

it('lists all testimonials ordered by sort', function () {
    $second = Testimonial::factory()->create(['sort' => 2, 'featured' => false]);
    $first = Testimonial::factory()->create(['sort' => 1, 'featured' => false]);

    $response = $this->getJson('/api/v1/testimonials')->assertOk();

    expect(collect($response->json('data'))->pluck('id')->all())->toBe([$first->id, $second->id]);

    $response->assertJsonStructure(['data' => [[
        'id', 'author', 'role', 'company', 'avatar_url', 'quote', 'rating', 'featured',
    ]]]);
});

it('filters to featured with ?featured=1', function () {
    $starred = Testimonial::factory()->featured()->create();
    Testimonial::factory()->create(['featured' => false]);

    $this->getJson('/api/v1/testimonials?featured=1')
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.id', $starred->id);
});
