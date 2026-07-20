<?php

namespace App\Filament\Admin\Widgets;

use App\Models\ContactMessage;
use App\Models\Post;
use App\Models\Project;
use App\Models\Testimonial;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected ?string $heading = 'At a glance';

    protected static ?int $sort = -2;

    protected function getStats(): array
    {
        $unread = ContactMessage::query()->whereNull('read_at')->count();

        return [
            Stat::make('Projects', Project::query()->count())
                ->description(Project::query()->where('featured', true)->count() . ' featured')
                ->descriptionIcon('heroicon-m-star')
                ->color('primary')
                ->chart([3, 5, 4, 7, 6, 8, 9]),

            Stat::make('Posts', Post::query()->count())
                ->description(Post::query()->published()->count() . ' published')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('info')
                ->chart([1, 2, 2, 3, 4, 4, 5]),

            Stat::make('Unread messages', $unread)
                ->description($unread > 0 ? 'You\'ve got mail' : 'Inbox zero ✨')
                ->descriptionIcon($unread > 0 ? 'heroicon-m-envelope' : 'heroicon-m-sparkles')
                ->color($unread > 0 ? 'warning' : 'success'),

            Stat::make('Testimonials', Testimonial::query()->count())
                ->description(Testimonial::query()->where('featured', true)->count() . ' featured')
                ->descriptionIcon('heroicon-m-heart')
                ->color('success'),
        ];
    }
}
