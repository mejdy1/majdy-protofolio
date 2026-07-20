<?php

use App\Enums\PostStatus;
use App\Enums\ProjectStatus;
use App\Models\ContactMessage;
use App\Models\Education;
use App\Models\Experience;
use App\Models\Post;
use App\Models\Project;
use App\Models\Service;
use App\Models\Skill;
use App\Models\SocialLink;
use App\Models\Testimonial;

describe('Post::published', function () {
    it('includes only published posts with a past publish date', function () {
        $visible = Post::factory()->create(['status' => PostStatus::Published, 'published_at' => now()->subDay()]);
        Post::factory()->create(['status' => PostStatus::Draft, 'published_at' => now()->subDay()]);
        Post::factory()->create(['status' => PostStatus::Scheduled, 'published_at' => now()->subDay()]);
        Post::factory()->create(['status' => PostStatus::Archived, 'published_at' => now()->subDay()]);
        Post::factory()->create(['status' => PostStatus::Published, 'published_at' => now()->addDay()]);
        Post::factory()->create(['status' => PostStatus::Published, 'published_at' => null]);

        expect(Post::published()->pluck('id')->all())->toBe([$visible->id]);
    });
});

describe('Project scopes', function () {
    it('published() filters by status', function () {
        $live = Project::factory()->create(['status' => ProjectStatus::Published]);
        Project::factory()->create(['status' => ProjectStatus::Draft]);
        Project::factory()->create(['status' => ProjectStatus::Archived]);

        expect(Project::published()->pluck('id')->all())->toBe([$live->id]);
    });

    it('featured() filters by flag', function () {
        $starred = Project::factory()->create(['featured' => true]);
        Project::factory()->create(['featured' => false]);

        expect(Project::featured()->pluck('id')->all())->toBe([$starred->id]);
    });

    it('ordered() sorts by sort asc, year desc, id desc', function () {
        $c = Project::factory()->create(['sort' => 1, 'year' => 2024]);
        $a = Project::factory()->create(['sort' => 0, 'year' => 2025]);
        $b = Project::factory()->create(['sort' => 0, 'year' => 2025]);
        $d = Project::factory()->create(['sort' => 0, 'year' => 2023]);

        expect(Project::ordered()->pluck('id')->all())->toBe([$b->id, $a->id, $d->id, $c->id]);
    });
});

describe('ordered() on simple models', function () {
    it('sorts skills by sort, category, name', function () {
        $b = Skill::factory()->create(['sort' => 0, 'category' => 'B', 'name' => 'x']);
        $a2 = Skill::factory()->create(['sort' => 0, 'category' => 'A', 'name' => 'z']);
        $a1 = Skill::factory()->create(['sort' => 0, 'category' => 'A', 'name' => 'a']);
        $last = Skill::factory()->create(['sort' => 5, 'category' => 'A', 'name' => 'a']);

        expect(Skill::ordered()->pluck('id')->all())->toBe([$a1->id, $a2->id, $b->id, $last->id]);
    });

    it('sorts experience by sort asc then start_date desc', function () {
        $old = Experience::factory()->create(['sort' => 0, 'start_date' => '2019-01-01']);
        $new = Experience::factory()->create(['sort' => 0, 'start_date' => '2024-01-01']);
        $pinnedLast = Experience::factory()->create(['sort' => 9, 'start_date' => '2026-01-01']);

        expect(Experience::ordered()->pluck('id')->all())->toBe([$new->id, $old->id, $pinnedLast->id]);
    });

    it('sorts education, services and social links by their sort columns', function () {
        $e2 = Education::factory()->create(['sort' => 2, 'start_date' => '2010-01-01']);
        $e1 = Education::factory()->create(['sort' => 1, 'start_date' => '2008-01-01']);
        expect(Education::ordered()->pluck('id')->all())->toBe([$e1->id, $e2->id]);

        $s2 = Service::factory()->create(['sort' => 2]);
        $s1 = Service::factory()->create(['sort' => 1]);
        expect(Service::ordered()->pluck('id')->all())->toBe([$s1->id, $s2->id]);

        $l2 = SocialLink::factory()->create(['sort' => 2]);
        $l1 = SocialLink::factory()->create(['sort' => 1]);
        expect(SocialLink::ordered()->pluck('id')->all())->toBe([$l1->id, $l2->id]);
    });
});

describe('Testimonial::featured', function () {
    it('filters by the featured flag', function () {
        $starred = Testimonial::factory()->featured()->create();
        Testimonial::factory()->create(['featured' => false]);

        expect(Testimonial::featured()->pluck('id')->all())->toBe([$starred->id]);
    });
});

describe('ContactMessage', function () {
    it('scopes unread to rows without read_at', function () {
        $unread = ContactMessage::factory()->create();
        ContactMessage::factory()->read()->create();

        expect(ContactMessage::unread()->pluck('id')->all())->toBe([$unread->id]);
    });

    it('markRead() stamps read_at once and never overwrites it', function () {
        $msg = ContactMessage::factory()->create();
        expect($msg->read_at)->toBeNull();

        $msg->markRead();
        $firstStamp = $msg->fresh()->read_at;
        expect($firstStamp)->not->toBeNull();

        $this->travel(1)->hours();
        $msg->fresh()->markRead();

        expect($msg->fresh()->read_at->equalTo($firstStamp))->toBeTrue();
    });
});
