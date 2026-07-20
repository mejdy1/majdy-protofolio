<?php

use App\Models\SocialLink;

it('lists social links ordered by sort', function () {
    $second = SocialLink::factory()->create(['sort' => 2]);
    $first = SocialLink::factory()->create(['sort' => 1]);

    $response = $this->getJson('/api/v1/social-links')->assertOk();

    expect(collect($response->json('data'))->pluck('id')->all())->toBe([$first->id, $second->id]);

    $response->assertJsonStructure(['data' => [['id', 'platform', 'url', 'handle', 'icon']]]);
});
