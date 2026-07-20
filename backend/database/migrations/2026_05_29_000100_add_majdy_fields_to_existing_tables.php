<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Downloadable recommendation-letter PDF
        Schema::table('testimonials', function (Blueprint $table) {
            $table->string('letter_path')->nullable()->after('avatar_url');
        });

        // Real file-upload paths (CV + images) alongside the legacy URL columns
        Schema::table('profiles', function (Blueprint $table) {
            $table->string('resume_path')->nullable()->after('resume_url');
            $table->string('avatar_path')->nullable()->after('avatar_url');
            $table->string('hero_image_path')->nullable()->after('hero_image_url');
        });

        // Structured case-study fields for the featured projects
        Schema::table('projects', function (Blueprint $table) {
            $table->longText('problem')->nullable()->after('body');
            $table->longText('solution')->nullable()->after('problem');
        });
    }

    public function down(): void
    {
        Schema::table('testimonials', function (Blueprint $table) {
            $table->dropColumn('letter_path');
        });
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn(['resume_path', 'avatar_path', 'hero_image_path']);
        });
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['problem', 'solution']);
        });
    }
};
