<?php

use App\Models\ContactMessage;

function validContactPayload(array $overrides = []): array
{
    return array_merge([
        'name' => 'Layla Hashim',
        'email' => 'layla@example.test',
        'subject' => 'Design system audit',
        'message' => 'I would like to talk about a design system audit for our product.',
    ], $overrides);
}

it('stores a message and replies 201', function () {
    $response = $this->postJson('/api/v1/contact', validContactPayload(), ['User-Agent' => 'PestSuite/1.0']);

    $response->assertCreated()->assertJsonStructure(['message', 'id']);

    $msg = ContactMessage::findOrFail($response->json('id'));
    expect($msg->name)->toBe('Layla Hashim')
        ->and($msg->email)->toBe('layla@example.test')
        ->and($msg->subject)->toBe('Design system audit')
        ->and($msg->ip)->toBe('127.0.0.1')
        ->and($msg->user_agent)->toBe('PestSuite/1.0')
        ->and($msg->read_at)->toBeNull();
});

it('truncates oversized user agents to 240 characters', function () {
    $response = $this->postJson('/api/v1/contact', validContactPayload(), ['User-Agent' => str_repeat('x', 300)]);

    $response->assertCreated();
    expect(strlen(ContactMessage::findOrFail($response->json('id'))->user_agent))->toBe(240);
});

it('stores a null subject when omitted', function () {
    $payload = validContactPayload();
    unset($payload['subject']);

    $response = $this->postJson('/api/v1/contact', $payload)->assertCreated();

    expect(ContactMessage::findOrFail($response->json('id'))->subject)->toBeNull();
});

it('rejects honeypot submissions', function () {
    $this->postJson('/api/v1/contact', validContactPayload(['website' => 'http://spam.example']))
        ->assertUnprocessable()
        ->assertJsonPath('errors.website.0', 'Spam protection triggered.');

    expect(ContactMessage::count())->toBe(0);
});

it('validates input', function (array $overrides, string $errorKey) {
    $this->postJson('/api/v1/contact', validContactPayload($overrides))
        ->assertUnprocessable()
        ->assertJsonValidationErrors($errorKey);

    expect(ContactMessage::count())->toBe(0);
})->with([
    'missing name' => [['name' => null], 'name'],
    'name too long' => [['name' => str_repeat('a', 121)], 'name'],
    'missing email' => [['email' => null], 'email'],
    'malformed email' => [['email' => 'not-an-email'], 'email'],
    'email too long' => [['email' => str_repeat('a', 195) . '@example.test'], 'email'],
    'subject too long' => [['subject' => str_repeat('a', 201)], 'subject'],
    'missing message' => [['message' => null], 'message'],
    'message too short' => [['message' => 'too short'], 'message'],
    'message too long' => [['message' => str_repeat('a', 5001)], 'message'],
]);

it('rate limits to three submissions per minute per ip', function () {
    foreach (range(1, 3) as $i) {
        $this->postJson('/api/v1/contact', validContactPayload(['subject' => "attempt {$i}"]))->assertCreated();
    }

    $this->postJson('/api/v1/contact', validContactPayload())
        ->assertStatus(429)
        ->assertHeader('Retry-After');

    expect(ContactMessage::count())->toBe(3);

    // A different IP is keyed separately.
    $this->withServerVariables(['REMOTE_ADDR' => '10.0.0.9'])
        ->postJson('/api/v1/contact', validContactPayload())
        ->assertCreated();

    expect(ContactMessage::count())->toBe(4);
});
