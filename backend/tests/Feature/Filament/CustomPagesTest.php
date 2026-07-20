<?php

use App\Filament\Admin\Pages\ProfileSettings;
use App\Filament\Admin\Pages\SiteSettings as SiteSettingsPage;
use App\Models\Profile;
use App\Models\SiteSetting;
use App\Models\User;
use Filament\Facades\Filament;
use Livewire\Livewire;

beforeEach(function () {
    $this->withoutVite();
    Filament::setCurrentPanel(Filament::getPanel('admin'));
    $this->actingAs(User::factory()->create());
});

describe('ProfileSettings', function () {
    it('renders', function () {
        $this->get('/admin/profile')->assertOk();
    });

    it('saves the singleton profile', function () {
        Livewire::test(ProfileSettings::class)
            ->fillForm(['name' => 'Edited Name', 'headline' => 'Edited Headline'])
            ->call('save')
            ->assertHasNoFormErrors();

        $profile = Profile::current();
        expect($profile->name)->toBe('Edited Name')
            ->and($profile->headline)->toBe('Edited Headline');
    });
});

describe('SiteSettings', function () {
    it('renders', function () {
        $this->get('/admin/site-settings')->assertOk();
    });

    it('persists settings', function () {
        // Only the fields shared by every design branch's variant of this
        // page are filled; branch-specific extras keep their mounted values.
        Livewire::test(SiteSettingsPage::class)
            ->fillForm([
                'seo_title' => 'Edited Title',
                'seo_description' => 'Edited description.',
                'contact_email' => 'edited@studio.test',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        expect(SiteSetting::get('seo.title'))->toBe('Edited Title')
            ->and(SiteSetting::get('seo.description'))->toBe('Edited description.')
            ->and(SiteSetting::get('contact.email'))->toBe('edited@studio.test');
    });
});
