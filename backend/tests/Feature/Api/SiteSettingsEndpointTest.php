<?php

use App\Models\SiteSetting;

/*
 * These assertions are deliberately limited to the contract shared by every
 * design branch: some branches extend the `theme` payload with extra keys
 * and different default accents, so we never assert exact defaults or the
 * absence of extra keys.
 */

it('returns the seo, theme and contact groups', function () {
    $response = $this->getJson('/api/v1/site-settings')->assertOk();

    $response->assertJsonStructure(['data' => ['seo' => ['title', 'description'], 'theme', 'contact' => ['email']]]);

    expect($response->json('data.theme.accent'))->toBeString()->not->toBe('')
        ->and($response->json('data.theme.accent_2'))->toBeString()->not->toBe('');
});

it('passes stored settings through', function () {
    SiteSetting::put('seo.title', 'My Studio');
    SiteSetting::put('seo.description', 'A portfolio.');
    SiteSetting::put('contact.email', 'mail@studio.test');
    SiteSetting::put('theme.accent', '#123456');

    $this->getJson('/api/v1/site-settings')
        ->assertOk()
        ->assertJsonPath('data.seo.title', 'My Studio')
        ->assertJsonPath('data.seo.description', 'A portfolio.')
        ->assertJsonPath('data.contact.email', 'mail@studio.test')
        ->assertJsonPath('data.theme.accent', '#123456');
});
