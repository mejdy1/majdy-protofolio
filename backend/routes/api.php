<?php

use App\Http\Controllers\Api\V1\CertificationController;
use App\Http\Controllers\Api\V1\ContactController;
use App\Http\Controllers\Api\V1\PostController;
use App\Http\Controllers\Api\V1\ProfileController;
use App\Http\Controllers\Api\V1\ProjectController;
use App\Http\Controllers\Api\V1\ResumeController;
use App\Http\Controllers\Api\V1\ServiceController;
use App\Http\Controllers\Api\V1\SiteSettingController;
use App\Http\Controllers\Api\V1\SkillController;
use App\Http\Controllers\Api\V1\SocialLinkController;
use App\Http\Controllers\Api\V1\TestimonialController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('/profile',         [ProfileController::class,      'show']);
    Route::get('/projects',        [ProjectController::class,      'index']);
    Route::get('/projects/{slug}', [ProjectController::class,      'show'])->name('projects.show');
    Route::get('/skills',          [SkillController::class,        'index']);
    Route::get('/experience',      [ResumeController::class,       'experience']);
    Route::get('/education',       [ResumeController::class,       'education']);
    Route::get('/services',        [ServiceController::class,      'index']);
    Route::get('/services/{slug}', [ServiceController::class,      'show']);
    Route::get('/testimonials',    [TestimonialController::class,  'index']);
    Route::get('/certifications',  [CertificationController::class, 'index']);
    Route::get('/posts',           [PostController::class,         'index']);
    Route::get('/posts/{slug}',    [PostController::class,         'show'])->name('posts.show');
    Route::get('/social-links',    [SocialLinkController::class,   'index']);
    Route::get('/site-settings',   [SiteSettingController::class,  'index']);

    Route::post('/contact', [ContactController::class, 'store'])
        ->middleware('throttle:contact');
});
