<?php

use App\Filament\Admin\Widgets\RecentMessages;
use App\Filament\Admin\Widgets\StatsOverview;
use App\Models\ContactMessage;
use App\Models\Project;
use App\Models\User;
use Filament\Facades\Filament;
use Livewire\Livewire;

beforeEach(function () {
    $this->withoutVite();
    Filament::setCurrentPanel(Filament::getPanel('admin'));
    $this->actingAs(User::factory()->create());
});

it('renders the stats overview with counts', function () {
    Project::factory()->count(2)->create();

    Livewire::test(StatsOverview::class)
        ->assertOk()
        ->assertSee('Projects')
        ->assertSee('Unread messages')
        ->assertSee('Inbox zero');
});

it('lists the five most recent contact messages', function () {
    foreach (range(1, 5) as $i) {
        ContactMessage::factory()->create(['created_at' => now()->subDays($i)]);
    }
    $latest = ContactMessage::factory()->create(['name' => 'Freshest Sender', 'created_at' => now()]);
    $oldest = ContactMessage::query()->orderBy('created_at')->first();

    Livewire::test(RecentMessages::class)
        ->assertOk()
        ->assertSee($latest->name)
        ->assertDontSee($oldest->name);
});
