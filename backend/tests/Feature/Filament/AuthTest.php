<?php

use App\Filament\Admin\Resources\Projects\ProjectResource;
use App\Models\User;
use Filament\Facades\Filament;

beforeEach(function () {
    $this->withoutVite();
    Filament::setCurrentPanel(Filament::getPanel('admin'));
});

it('redirects guests to the login page', function () {
    $this->get('/admin')->assertRedirect('/admin/login');
    $this->get(ProjectResource::getUrl('index'))->assertRedirect('/admin/login');
});

it('renders the login page', function () {
    $this->get('/admin/login')->assertOk();
});

it('lets an authenticated user open the dashboard', function () {
    // Pins the FilamentUser contract fix — without it this 403s
    // because APP_ENV is "testing", not "local".
    $this->actingAs(User::factory()->create())
        ->get('/admin')
        ->assertOk();
});
