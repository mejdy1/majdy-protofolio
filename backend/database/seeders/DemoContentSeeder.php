<?php

namespace Database\Seeders;

use App\Enums\PostStatus;
use App\Enums\ProjectStatus;
use App\Enums\ServiceTier;
use App\Enums\SkillLevel;
use App\Models\Category;
use App\Models\Certification;
use App\Models\ContactMessage;
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
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DemoContentSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user ------------------------------------------------------
        User::updateOrCreate(
            ['email' => 'majdy@portfolio.test'],
            [
                'name' => 'Majdy',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        // Profile (singleton) --------------------------------------------
        Profile::updateOrCreate(['id' => 1], [
            'name' => 'Majdy Al-Harbi',
            'headline' => 'Computer Science Graduate · Software Engineer',
            'tagline' => 'I build dependable, well-crafted software — from clean back-end systems to interfaces that feel effortless.',
            'bio' => '<p>I am a Computer Science graduate from Umm Al-Qura University with a focus on full-stack web development. I enjoy turning fuzzy problems into clear, maintainable systems — and I care about the small details that make software feel reliable.</p><p>Recently I led my senior graduation project and completed a co-op training program building real production back-ends. I am looking for a software engineering role where I can keep learning and ship work that matters.</p>',
            'location' => 'Makkah, Saudi Arabia',
            'email' => 'majdy@example.com',
            'phone' => '0500071581',
            'avatar_url' => 'https://i.pravatar.cc/512?img=13',
            'resume_url' => null,
            'hero_image_url' => 'https://picsum.photos/seed/majdy-hero/1920/1080',
            'available_for_hire' => true,
            'roles' => ['Software Engineer', 'Back-end Developer', 'Problem Solver', 'CS Graduate'],
        ]);

        // Categories (only needed for the sample posts; nav hidden) -------
        $cats = collect([
            ['name' => 'Notes', 'color' => '#3B6EF6'],
        ])->map(fn ($c) => Category::updateOrCreate(['slug' => Str::slug($c['name'])], $c + ['slug' => Str::slug($c['name'])]));

        // Projects --------------------------------------------------------
        // Exactly TWO featured (Graduation + Training) carry problem/solution;
        // the rest are smaller "other" projects.
        $projectsData = [
            [
                'title' => 'GradPath — Smart Academic Advising Platform',
                'summary' => 'Academic advising ran on spreadsheets and long office queues — students struggled to plan eligible courses each term.',
                'problem' => '<p>Every registration period, students at the College of Computing waited in long advising queues while advisors manually cross-checked prerequisites, GPA thresholds and schedule clashes in spreadsheets. Mistakes were common and the process simply did not scale.</p>',
                'solution' => '<p><strong>GradPath</strong> models the full degree plan as a prerequisite graph and recommends a conflict-free set of eligible courses for each student in seconds. Advisors review and approve plans from a dashboard while students track their progress toward graduation.</p><ul><li>Rule engine validating prerequisites, co-requisites and GPA gates</li><li>Automatic, clash-free schedule suggestions</li><li>Advisor approval workflow with full audit history</li></ul>',
                'role' => 'Team Lead · Full-stack Developer',
                'client' => 'Umm Al-Qura University — Senior Capstone',
                'year' => 2024,
                'stack' => ['Laravel', 'Vue', 'MySQL', 'Tailwind', 'REST API'],
                'featured' => true,
            ],
            [
                'title' => 'FleetTrack — Logistics Operations Dashboard',
                'summary' => 'During my co-op training, dispatchers had no single live view of deliveries, so status updates happened over phone calls.',
                'problem' => '<p>At my training company, the operations team coordinated dozens of daily deliveries by phone and chat. There was no single source of truth for vehicle status, and end-of-day reporting was assembled by hand.</p>',
                'solution' => '<p>I built the back-end and a real-time dashboard for <strong>FleetTrack</strong>, giving dispatchers a live map of every vehicle, automated delivery timelines, and one-click daily reports.</p><ul><li>REST API and WebSocket layer for live vehicle telemetry</li><li>Role-based dashboard for dispatchers and managers</li><li>Automated daily and weekly performance reports</li></ul>',
                'role' => 'Back-end Developer — Co-op Intern',
                'client' => 'Co-op Training — Acme Logistics',
                'year' => 2023,
                'stack' => ['Node.js', 'Express', 'PostgreSQL', 'React', 'Docker'],
                'featured' => true,
            ],
            [
                'title' => 'Algorithm Visualizer',
                'summary' => 'An interactive playground that animates sorting and pathfinding algorithms step by step.',
                'role' => 'Solo Developer',
                'client' => 'Personal',
                'year' => 2023,
                'stack' => ['JavaScript', 'Canvas', 'HTML', 'CSS'],
                'featured' => false,
            ],
            [
                'title' => 'DevConnect — Developer Q&A Community',
                'summary' => 'A Stack-Overflow-style Q&A app with voting, tags and reputation.',
                'role' => 'Full-stack Developer',
                'client' => 'Personal',
                'year' => 2023,
                'stack' => ['Laravel', 'Livewire', 'MySQL', 'Tailwind'],
                'featured' => false,
            ],
            [
                'title' => 'Pomodoro CLI',
                'summary' => 'A tiny terminal productivity timer with session logging and weekly stats.',
                'role' => 'Solo Developer',
                'client' => 'Open Source',
                'year' => 2022,
                'stack' => ['Python', 'Click', 'SQLite'],
                'featured' => false,
            ],
            [
                'title' => 'Weather Now — PWA',
                'summary' => 'A fast, installable weather app with location search and offline caching.',
                'role' => 'Solo Developer',
                'client' => 'Personal',
                'year' => 2022,
                'stack' => ['Vue', 'Vite', 'OpenWeather API'],
                'featured' => false,
            ],
        ];

        foreach ($projectsData as $i => $data) {
            Project::updateOrCreate(
                ['slug' => Str::slug($data['title'])],
                array_merge($data, [
                    'slug' => Str::slug($data['title']),
                    'body' => "<p>{$data['summary']}</p><p>Built with a focus on clean architecture, testing and a smooth, accessible user experience.</p>",
                    'cover_url' => 'https://picsum.photos/seed/'.Str::slug($data['title']).'/1600/1000',
                    'gallery' => [
                        'https://picsum.photos/seed/'.Str::slug($data['title']).'-1/1600/1000',
                        'https://picsum.photos/seed/'.Str::slug($data['title']).'-2/1600/1000',
                    ],
                    'live_url' => 'https://example.com/'.Str::slug($data['title']),
                    'repo_url' => 'https://github.com/majdy/'.Str::slug($data['title']),
                    'status' => ProjectStatus::Published,
                    'sort' => $i,
                ])
            );
        }

        // Skills (trimmed CS set; nav hidden, not rendered on this site) ---
        $skills = [
            ['Languages',  'Java',        'logos:java',        SkillLevel::Advanced,     3, '#E76F00'],
            ['Languages',  'Python',      'logos:python',      SkillLevel::Advanced,     3, '#3776AB'],
            ['Languages',  'JavaScript',  'logos:javascript',  SkillLevel::Advanced,     3, '#F7DF1E'],
            ['Frameworks', 'Laravel',     'logos:laravel',     SkillLevel::Advanced,     2, '#FF2D20'],
            ['Frameworks', 'Vue',         'logos:vue',         SkillLevel::Intermediate, 2, '#42B883'],
            ['Frameworks', 'React',       'logos:react',       SkillLevel::Intermediate, 2, '#61DAFB'],
            ['Data',       'MySQL',       'logos:mysql',       SkillLevel::Advanced,     3, '#4479A1'],
            ['Tools',      'Git',         'logos:git-icon',    SkillLevel::Advanced,     3, '#F05032'],
        ];
        foreach ($skills as $i => [$cat, $name, $icon, $level, $years, $color]) {
            Skill::updateOrCreate(
                ['name' => $name],
                ['category' => $cat, 'icon' => $icon, 'proficiency' => $level, 'years_experience' => $years, 'color' => $color, 'sort' => $i]
            );
        }

        // Experience ------------------------------------------------------
        $experiences = [
            [
                'role' => 'Software Engineering Intern',
                'company' => 'Acme Logistics',
                'company_url' => null,
                'location' => 'Makkah · Co-op Training',
                'start_date' => '2024-02-01',
                'end_date' => '2024-08-01',
                'current' => false,
                'description' => 'Six-month cooperative training building back-end services and a real-time operations dashboard for a logistics platform.',
                'achievements' => [
                    'Designed REST APIs consumed by web and mobile clients',
                    'Added a WebSocket layer for live vehicle tracking',
                    'Wrote the automated daily reporting pipeline',
                ],
                'stack' => ['Node.js', 'Express', 'PostgreSQL', 'Docker'],
            ],
            [
                'role' => 'Teaching Assistant — Data Structures',
                'company' => 'Umm Al-Qura University',
                'company_url' => null,
                'location' => 'Makkah',
                'start_date' => '2023-09-01',
                'end_date' => '2024-06-01',
                'current' => false,
                'description' => 'Led weekly lab sessions and mentored students through Data Structures and Introduction to Programming.',
                'achievements' => [
                    'Ran labs and office hours for 60+ students',
                    'Authored practice problem sets and auto-graded assignments',
                ],
                'stack' => ['Java', 'C++', 'Algorithms'],
            ],
            [
                'role' => 'Freelance Web Developer',
                'company' => 'Self-employed',
                'company_url' => null,
                'location' => 'Remote',
                'start_date' => '2022-01-01',
                'end_date' => null,
                'current' => true,
                'description' => 'Designed and shipped websites and small web apps for local businesses and fellow students.',
                'achievements' => [
                    'Delivered 10+ responsive sites end to end',
                    'Handled hosting, deployment and client hand-off',
                ],
                'stack' => ['Laravel', 'Vue', 'Tailwind', 'MySQL'],
            ],
            [
                'role' => 'Open-Source Contributor',
                'company' => 'Community',
                'company_url' => null,
                'location' => 'Remote',
                'start_date' => '2021-06-01',
                'end_date' => null,
                'current' => true,
                'description' => 'Contributed bug fixes, tests and documentation to PHP and JavaScript open-source projects.',
                'achievements' => [
                    'Merged fixes into several popular libraries',
                    'Improved docs and onboarding for new contributors',
                ],
                'stack' => ['PHP', 'JavaScript', 'Git'],
            ],
        ];
        foreach ($experiences as $i => $exp) {
            Experience::updateOrCreate(
                ['role' => $exp['role'], 'company' => $exp['company']],
                $exp + ['sort' => $i]
            );
        }

        // Education -------------------------------------------------------
        Education::updateOrCreate(
            ['school' => 'Umm Al-Qura University', 'degree' => 'B.Sc. Computer Science'],
            [
                'field' => 'Computer Science',
                'start_date' => '2020-09-01',
                'end_date' => '2024-06-01',
                'description' => 'Graduated with honors. Senior capstone: GradPath academic advising platform.',
                'sort' => 0,
            ]
        );

        // Services (spec has no services section; one hidden placeholder) --
        Service::updateOrCreate(
            ['slug' => 'web-development'],
            [
                'title' => 'Web Development',
                'slug' => 'web-development',
                'summary' => 'Full-stack web apps with Laravel and Vue.',
                'body' => '<p>Available for software engineering roles, internships and freelance projects.</p>',
                'icon' => 'heroicon-o-code-bracket',
                'tier' => ServiceTier::Standard,
                'starting_price' => null,
                'features' => ['Laravel APIs', 'Vue front-ends', 'Responsive design'],
                'sort' => 0,
            ]
        );

        // Testimonials — exactly 3 university doctors (letters uploaded in CMS)
        $testimonials = [
            ['Dr. Ahmed Al-Harbi', 'Professor of Computer Science', 'Umm Al-Qura University', 'https://i.pravatar.cc/200?img=11', 'Majdy was among the most capable students in his cohort. He pairs strong fundamentals with genuine ownership — his graduation project was production-quality.', 5, true],
            ['Dr. Sara Al-Otaibi', 'Associate Professor of Software Engineering', 'Umm Al-Qura University', 'https://i.pravatar.cc/200?img=45', 'A rare combination of rigor and creativity. Majdy consistently delivered clean, well-tested code and lifted the whole team around him.', 5, true],
            ['Dr. Khalid Al-Zahrani', 'Lecturer, Algorithms & Data Structures', 'Umm Al-Qura University', 'https://i.pravatar.cc/200?img=68', 'As his lab supervisor I watched Majdy explain hard concepts with remarkable clarity. He is dependable, humble and a natural engineer.', 5, true],
        ];
        foreach ($testimonials as $i => [$author, $role, $company, $avatar, $quote, $rating, $featured]) {
            Testimonial::updateOrCreate(
                ['author' => $author],
                ['role' => $role, 'company' => $company, 'avatar_url' => $avatar, 'letter_path' => null, 'quote' => $quote, 'rating' => $rating, 'featured' => $featured, 'sort' => $i]
            );
        }

        // Certifications (PDFs uploaded later in the CMS) -----------------
        $certifications = [
            ['AWS Certified Cloud Practitioner',      'Amazon Web Services',          '2024-03-15', 'AWS-CCP-2024-1183', 'https://www.credly.com/',                   true,  0],
            ['Meta Back-End Developer',               'Meta · Coursera',              '2023-11-02', 'META-BE-7741',      'https://coursera.org/verify/',              true,  1],
            ['Machine Learning Specialization',       'DeepLearning.AI · Coursera',   '2024-01-20', 'ML-SPEC-5520',      'https://coursera.org/verify/',              true,  2],
            ['Oracle Certified Associate, Java SE 8', 'Oracle',                       '2023-02-18', 'OCA-JAVA-9921',     'https://catalog-education.oracle.com/',     false, 3],
            ['Google Data Analytics',                 'Google · Coursera',            '2023-08-09', 'GDA-3340',          'https://coursera.org/verify/',              false, 4],
            ['Responsive Web Design',                 'freeCodeCamp',                 '2022-06-12', 'FCC-RWD-2210',      'https://freecodecamp.org/certification/',   false, 5],
        ];
        foreach ($certifications as [$title, $issuer, $issued, $cid, $curl, $featured, $sort]) {
            Certification::updateOrCreate(
                ['title' => $title],
                ['issuer' => $issuer, 'issue_date' => $issued, 'credential_id' => $cid, 'credential_url' => $curl, 'featured' => $featured, 'sort' => $sort, 'file_path' => null]
            );
        }

        // Posts (minimal; nav hidden, not rendered on this site) ----------
        $posts = [
            ['What I learned building my graduation project', 'Notes', 'Lessons from shipping a real platform with a student team.'],
            ["A back-end intern's first month",               'Notes', 'Notes from co-op training: tests, code review and shipping carefully.'],
        ];
        foreach ($posts as $i => [$title, $catName, $excerpt]) {
            $cat = $cats->firstWhere('name', $catName);
            Post::updateOrCreate(
                ['slug' => Str::slug($title)],
                [
                    'title' => $title,
                    'slug' => Str::slug($title),
                    'excerpt' => $excerpt,
                    'body' => "<p>{$excerpt}</p><p>The full write-up lives here — edit it from the admin panel.</p>",
                    'cover_url' => 'https://picsum.photos/seed/'.Str::slug($title).'/1600/900',
                    'reading_time' => 4 + $i,
                    'published_at' => Carbon::now()->subWeeks($i + 1),
                    'status' => PostStatus::Published,
                    'featured' => $i < 1,
                    'category_id' => $cat?->id,
                ]
            );
        }

        // Social Links — professional links only --------------------------
        SocialLink::where('platform', 'GitHub')->delete();
        $socials = [
            ['LinkedIn', 'https://www.linkedin.com/in/majdi-alkediwi', 'majdi-alkediwi', 'simple-icons:linkedin'],
        ];
        foreach ($socials as $i => [$p, $u, $h, $icon]) {
            SocialLink::updateOrCreate(
                ['platform' => $p],
                ['url' => $u, 'handle' => $h, 'icon' => $icon, 'sort' => $i]
            );
        }

        // Site settings — royal/ambient blue accent -----------------------
        SiteSetting::put('seo.title',       'Majdy — Computer Science Graduate',                                          'seo', 'text');
        SiteSetting::put('seo.description', 'Portfolio of Majdy Al-Harbi — Computer Science graduate and software engineer.', 'seo', 'textarea');
        SiteSetting::put('theme.accent',    '#1D4ED8', 'theme', 'color');
        SiteSetting::put('theme.accent_2',  '#3B82F6', 'theme', 'color');
        SiteSetting::put('contact.email',   'majdy@example.com', 'contact', 'email');

        // Sample downloadable PDFs (placeholders — replace them in the CMS) -
        $disk = Storage::disk('public');
        $cvPath = 'cv/sample-majdy-cv.pdf';
        $disk->put($cvPath, $this->placeholderPdf('Majdy Al-Harbi - CV'));
        Profile::where('id', 1)->update(['resume_path' => $cvPath]);

        foreach (Testimonial::all() as $t) {
            $path = "letters/letter-{$t->id}.pdf";
            $disk->put($path, $this->placeholderPdf('Recommendation Letter - ' . $t->author));
            $t->update(['letter_path' => $path]);
        }

        foreach (Certification::where('featured', true)->get() as $c) {
            $path = "certifications/cert-{$c->id}.pdf";
            $disk->put($path, $this->placeholderPdf($c->title));
            $c->update(['file_path' => $path]);
        }

        // Sample contact messages (populate the dashboard inbox) ----------
        ContactMessage::create([
            'name' => 'Nora Saleh',
            'email' => 'nora@example.com',
            'subject' => 'Internship opportunity',
            'message' => "Hi Majdy, we reviewed your graduation project and would love to talk about a back-end role on our team.",
            'ip' => '127.0.0.1',
            'user_agent' => 'Mozilla/5.0 demo',
        ]);
        ContactMessage::create([
            'name' => 'Omar Aziz',
            'email' => 'omar@example.com',
            'subject' => 'Freelance website',
            'message' => "Could you help build a small business website? Happy to share the details.",
            'ip' => '127.0.0.1',
            'user_agent' => 'Mozilla/5.0 demo',
            'read_at' => now()->subDay(),
        ]);
    }

    /** Build a minimal valid one-page PDF used as placeholder download content. */
    private function placeholderPdf(string $title): string
    {
        $safe = str_replace(['(', ')', '\\'], '', $title);
        $objs = [
            '<< /Type /Catalog /Pages 2 0 R >>',
            '<< /Type /Pages /Kids [3 0 R] /Count 1 >>',
            '<< /Type /Page /Parent 2 0 R /MediaBox [0 0 595 842] /Resources << /Font << /F1 5 0 R >> >> /Contents 4 0 R >>',
        ];
        $text = "BT /F1 26 Tf 60 760 Td ($safe) Tj 0 -40 Td /F1 12 Tf (Placeholder document - replace this file in the CMS.) Tj ET";
        $objs[] = "<< /Length " . strlen($text) . " >>\nstream\n" . $text . "\nendstream";
        $objs[] = '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>';

        $pdf = "%PDF-1.4\n";
        $offsets = [];
        foreach ($objs as $i => $obj) {
            $offsets[] = strlen($pdf);
            $pdf .= ($i + 1) . " 0 obj\n" . $obj . "\nendobj\n";
        }
        $xref = strlen($pdf);
        $pdf .= "xref\n0 " . (count($objs) + 1) . "\n0000000000 65535 f \n";
        foreach ($offsets as $off) {
            $pdf .= sprintf("%010d 00000 n \n", $off);
        }
        $pdf .= "trailer\n<< /Size " . (count($objs) + 1) . " /Root 1 0 R >>\nstartxref\n" . $xref . "\n%%EOF";

        return $pdf;
    }
}
