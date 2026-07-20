<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('headline');
            $table->string('tagline')->nullable();
            $table->longText('bio')->nullable();
            $table->string('location')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('avatar_url')->nullable();
            $table->string('resume_url')->nullable();
            $table->string('hero_image_url')->nullable();
            $table->boolean('available_for_hire')->default(true);
            $table->json('roles')->nullable();
            $table->timestamps();
        });

        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('color', 16)->default('#7C5CFF');
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('summary');
            $table->longText('body')->nullable();
            $table->string('cover_url')->nullable();
            $table->json('gallery')->nullable();
            $table->json('stack')->nullable();
            $table->string('live_url')->nullable();
            $table->string('repo_url')->nullable();
            $table->string('client')->nullable();
            $table->string('role')->nullable();
            $table->unsignedSmallInteger('year')->nullable();
            $table->boolean('featured')->default(false);
            $table->string('status')->default('published')->index();
            $table->unsignedInteger('sort')->default(0)->index();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['featured', 'status']);
        });

        Schema::create('skills', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('icon')->nullable();
            $table->string('category')->index();
            $table->string('proficiency')->default('advanced');
            $table->unsignedTinyInteger('years_experience')->default(1);
            $table->string('color', 16)->default('#7C5CFF');
            $table->unsignedInteger('sort')->default(0)->index();
            $table->timestamps();
        });

        Schema::create('experiences', function (Blueprint $table) {
            $table->id();
            $table->string('role');
            $table->string('company');
            $table->string('company_url')->nullable();
            $table->string('location')->nullable();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->boolean('current')->default(false);
            $table->longText('description')->nullable();
            $table->json('achievements')->nullable();
            $table->json('stack')->nullable();
            $table->unsignedInteger('sort')->default(0)->index();
            $table->timestamps();
        });

        Schema::create('educations', function (Blueprint $table) {
            $table->id();
            $table->string('school');
            $table->string('degree');
            $table->string('field')->nullable();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->longText('description')->nullable();
            $table->unsignedInteger('sort')->default(0)->index();
            $table->timestamps();
        });

        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('summary');
            $table->longText('body')->nullable();
            $table->string('icon')->nullable();
            $table->string('tier')->default('standard');
            $table->string('starting_price')->nullable();
            $table->json('features')->nullable();
            $table->unsignedInteger('sort')->default(0)->index();
            $table->timestamps();
        });

        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->string('author');
            $table->string('role')->nullable();
            $table->string('company')->nullable();
            $table->string('avatar_url')->nullable();
            $table->longText('quote');
            $table->unsignedTinyInteger('rating')->default(5);
            $table->boolean('featured')->default(false);
            $table->unsignedInteger('sort')->default(0)->index();
            $table->timestamps();
        });

        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('excerpt');
            $table->longText('body');
            $table->string('cover_url')->nullable();
            $table->unsignedSmallInteger('reading_time')->default(3);
            $table->timestamp('published_at')->nullable()->index();
            $table->string('status')->default('draft')->index();
            $table->boolean('featured')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('subject')->nullable();
            $table->longText('message');
            $table->string('ip', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamp('replied_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('social_links', function (Blueprint $table) {
            $table->id();
            $table->string('platform');
            $table->string('url');
            $table->string('handle')->nullable();
            $table->string('icon')->nullable();
            $table->unsignedInteger('sort')->default(0)->index();
            $table->timestamps();
        });

        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('group')->default('general')->index();
            $table->json('value')->nullable();
            $table->string('label')->nullable();
            $table->string('type')->default('text');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_settings');
        Schema::dropIfExists('social_links');
        Schema::dropIfExists('contact_messages');
        Schema::dropIfExists('posts');
        Schema::dropIfExists('testimonials');
        Schema::dropIfExists('services');
        Schema::dropIfExists('educations');
        Schema::dropIfExists('experiences');
        Schema::dropIfExists('skills');
        Schema::dropIfExists('projects');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('profiles');
    }
};
