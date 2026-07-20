<?php

use App\Models\Profile;

it('auto-creates the singleton profile when none exists', function () {
    $this->getJson('/api/v1/profile')
        ->assertOk()
        ->assertJsonPath('data.name', 'Your Name')
        ->assertJsonPath('data.available_for_hire', true);

    expect(Profile::count())->toBe(1);
});

it('returns the stored profile with the full field shape', function () {
    Profile::factory()->create([
        'id' => 1,
        'name' => 'Test Person',
        'headline' => 'Maker of Things',
        'roles' => ['Engineer', 'Designer'],
        'available_for_hire' => false,
    ]);

    $this->getJson('/api/v1/profile')
        ->assertOk()
        ->assertJsonPath('data.name', 'Test Person')
        ->assertJsonPath('data.headline', 'Maker of Things')
        ->assertJsonPath('data.roles', ['Engineer', 'Designer'])
        ->assertJsonPath('data.available_for_hire', false)
        ->assertJsonStructure(['data' => [
            'name', 'headline', 'tagline', 'bio', 'location', 'email', 'phone',
            'avatar_url', 'hero_image_url', 'resume_url', 'available_for_hire', 'roles',
        ]]);
});
