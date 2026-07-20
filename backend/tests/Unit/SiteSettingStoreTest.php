<?php

use App\Models\SiteSetting;

it('persists key, value, group and type through put()', function () {
    SiteSetting::put('seo.title', 'Hello', 'seo', 'text');

    $row = SiteSetting::where('key', 'seo.title')->first();

    expect($row)->not->toBeNull()
        ->and($row->value)->toBe('Hello')
        ->and($row->group)->toBe('seo')
        ->and($row->type)->toBe('text');
});

it('round-trips array values through the json column', function () {
    SiteSetting::put('misc.list', ['a' => 1, 'b' => [2, 3]]);

    expect(SiteSetting::get('misc.list'))->toBe(['a' => 1, 'b' => [2, 3]]);
});

it('returns the default when the key is missing', function () {
    expect(SiteSetting::get('missing.key', 'fallback'))->toBe('fallback');
});

it('does not pin a previously-returned default after put()', function () {
    // get() rememberForever()s the default; put()/saved hooks must clear it.
    expect(SiteSetting::get('k', 'a'))->toBe('a');

    SiteSetting::put('k', 'b');

    expect(SiteSetting::get('k'))->toBe('b');
});

it('updates an existing key in place', function () {
    SiteSetting::put('k', 'one');
    SiteSetting::put('k', 'two');

    expect(SiteSetting::where('key', 'k')->count())->toBe(1)
        ->and(SiteSetting::get('k'))->toBe('two');
});

it('forgets the cached value when the row is deleted', function () {
    SiteSetting::put('k', 'kept');
    expect(SiteSetting::get('k'))->toBe('kept');

    SiteSetting::where('key', 'k')->first()->delete();

    expect(SiteSetting::get('k', 'gone'))->toBe('gone');
});
