<?php

use App\Models\Profile;

it('creates the singleton row with defaults on first access', function () {
    expect(Profile::count())->toBe(0);

    $profile = Profile::current();

    expect($profile->id)->toBe(1)
        ->and($profile->name)->toBe('Your Name')
        ->and($profile->available_for_hire)->toBeTrue()
        ->and($profile->roles)->toBe(['Designer', 'Engineer', 'Maker'])
        ->and(Profile::count())->toBe(1);
});

it('returns the same row on subsequent access without duplicating', function () {
    $first = Profile::current();
    $first->update(['name' => 'Custom Name']);

    $second = Profile::current();

    expect($second->id)->toBe($first->id)
        ->and($second->name)->toBe('Custom Name')
        ->and(Profile::count())->toBe(1);
});
