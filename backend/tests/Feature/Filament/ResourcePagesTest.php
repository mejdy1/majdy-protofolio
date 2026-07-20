<?php

use App\Models\User;
use Filament\Facades\Filament;

beforeEach(function () {
    $this->withoutVite();
    Filament::setCurrentPanel(Filament::getPanel('admin'));
    $this->actingAs(User::factory()->create());
});

dataset('resources', [
    'projects' => [
        \App\Filament\Admin\Resources\Projects\ProjectResource::class,
        fn () => \App\Models\Project::factory()->create(),
    ],
    'posts' => [
        \App\Filament\Admin\Resources\Posts\PostResource::class,
        fn () => \App\Models\Post::factory()->create(),
    ],
    'categories' => [
        \App\Filament\Admin\Resources\Categories\CategoryResource::class,
        fn () => \App\Models\Category::factory()->create(),
    ],
    'skills' => [
        \App\Filament\Admin\Resources\Skills\SkillResource::class,
        fn () => \App\Models\Skill::factory()->create(),
    ],
    'experiences' => [
        \App\Filament\Admin\Resources\Experiences\ExperienceResource::class,
        fn () => \App\Models\Experience::factory()->create(),
    ],
    'education' => [
        \App\Filament\Admin\Resources\Education\EducationResource::class,
        fn () => \App\Models\Education::factory()->create(),
    ],
    'services' => [
        \App\Filament\Admin\Resources\Services\ServiceResource::class,
        fn () => \App\Models\Service::factory()->create(),
    ],
    'testimonials' => [
        \App\Filament\Admin\Resources\Testimonials\TestimonialResource::class,
        fn () => \App\Models\Testimonial::factory()->create(),
    ],
    'contact messages' => [
        \App\Filament\Admin\Resources\ContactMessages\ContactMessageResource::class,
        fn () => \App\Models\ContactMessage::factory()->create(),
    ],
    'social links' => [
        \App\Filament\Admin\Resources\SocialLinks\SocialLinkResource::class,
        fn () => \App\Models\SocialLink::factory()->create(),
    ],
]);

it('renders the list page', function (string $resource, Closure $factory) {
    $this->get($resource::getUrl('index'))->assertOk();
})->with('resources');

it('renders the create page', function (string $resource, Closure $factory) {
    $this->get($resource::getUrl('create'))->assertOk();
})->with('resources');

it('renders the view page for a record', function (string $resource, Closure $factory) {
    $record = $factory();

    $this->get($resource::getUrl('view', ['record' => $record]))->assertOk();
})->with('resources');

it('renders the edit page for a record', function (string $resource, Closure $factory) {
    $record = $factory();

    $this->get($resource::getUrl('edit', ['record' => $record]))->assertOk();
})->with('resources');
