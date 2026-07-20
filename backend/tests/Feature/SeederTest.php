<?php

use App\Models\Post;
use App\Models\Profile;
use App\Models\Project;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;

it('seeds demo content and is idempotent', function () {
    $this->seed(DatabaseSeeder::class);

    expect(User::where('email', 'majdy@portfolio.test')->exists())->toBeTrue()
        ->and(Profile::count())->toBe(1)
        ->and(Project::count())->toBeGreaterThan(0)
        ->and(Post::count())->toBeGreaterThan(0);

    $counts = [User::count(), Profile::count(), Project::count(), Post::count()];

    $this->seed(DatabaseSeeder::class);

    expect([User::count(), Profile::count(), Project::count(), Post::count()])->toBe($counts);
});
