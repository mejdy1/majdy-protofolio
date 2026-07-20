<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Certification;
use App\Models\Education;
use App\Models\Experience;
use App\Models\Post;
use App\Models\Profile;
use App\Models\Project;
use App\Models\Service;
use App\Models\SiteSetting;
use App\Models\Skill;
use App\Models\SocialLink;
use App\Models\Testimonial;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        RateLimiter::for('contact', fn (Request $request) =>
            Limit::perMinute(3)->by($request->ip())
        );

        // The API caches under parameterized keys (api:projects:f=…:l=…,
        // api:post:{slug}, api:posts:c=…:p=…) that a fixed forget-list can
        // never enumerate, and the database cache store has no tag support —
        // so content saves flush the whole store. Saves only happen from the
        // single-author admin; the cost is one cold miss per endpoint.
        // ContactMessage is deliberately NOT hooked: it is written by the
        // public POST /contact endpoint, and flushing there would reset the
        // sender's own throttle bucket.
        $bust = fn () => Cache::flush();

        foreach ([
            Profile::class, Project::class, Skill::class, Service::class,
            Post::class, Testimonial::class, SocialLink::class,
            Experience::class, Education::class, Category::class, Certification::class, SiteSetting::class,
        ] as $model) {
            $model::saved($bust);
            $model::deleted($bust);
        }

    }
}
