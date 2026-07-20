<?php

namespace App\Providers\Filament;

use Filament\Enums\ThemeMode;
use Filament\Support\Enums\Width;
use Filament\FontProviders\GoogleFontProvider;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentAsset;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('admin')
            ->path('admin')
            ->login()
            ->brandName('Majdi')
            ->favicon(asset('favicon.svg'))
            ->defaultThemeMode(ThemeMode::Dark)
            ->maxContentWidth(Width::Full)
            ->sidebarCollapsibleOnDesktop()
            ->colors([
                // Royal / ambient blue — the primary
                'primary' => [
                    50  => '#EFF4FF',
                    100 => '#DBE6FE',
                    200 => '#BFD3FE',
                    300 => '#93B4FD',
                    400 => '#6090FA',
                    500 => '#3B6FF6',   // royal blue
                    600 => '#1D4ED8',   // pressed
                    700 => '#1E40AF',
                    800 => '#1E3A8A',
                    900 => '#172554',
                    950 => '#0F1733',
                ],
                'success' => Color::Emerald,
                'warning' => Color::Amber,
                'danger'  => Color::Rose,
                'info'    => Color::Sky,
                'gray'    => Color::Slate,
            ])
            // General Sans + Fraunces + IBM Plex Mono loaded via @import inside theme.css
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->navigationGroups([
                NavigationGroup::make('Studio')->icon('heroicon-o-book-open'),
                NavigationGroup::make('Portfolio')->icon('heroicon-o-rectangle-stack'),
                NavigationGroup::make('Writing')->icon('heroicon-o-pencil-square'),
                NavigationGroup::make('Inbox')->icon('heroicon-o-envelope'),
                NavigationGroup::make('Settings')->icon('heroicon-o-beaker')->collapsed(),
            ])
            ->discoverResources(in: app_path('Filament/Admin/Resources'), for: 'App\\Filament\\Admin\\Resources')
            ->discoverPages(in: app_path('Filament/Admin/Pages'), for: 'App\\Filament\\Admin\\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Admin/Widgets'), for: 'App\\Filament\\Admin\\Widgets')
            ->widgets([
                \App\Filament\Admin\Widgets\StatsOverview::class,
                \App\Filament\Admin\Widgets\RecentMessages::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }

    public function boot(): void
    {
        FilamentAsset::register([
            Js::make('admin-extras', resource_path('js/admin-extras.js')),
        ]);
    }
}
