<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Cache;

class SiteSettingController extends Controller
{
    public function index()
    {
        $data = Cache::flexible('api:settings', [60, 600], fn () => [
            'seo' => [
                'title'       => SiteSetting::get('seo.title'),
                'description' => SiteSetting::get('seo.description'),
            ],
            'theme' => [
                'accent'   => SiteSetting::get('theme.accent', '#7C5CFF'),
                'accent_2' => SiteSetting::get('theme.accent_2', '#22D3EE'),
            ],
            'contact' => [
                'email' => SiteSetting::get('contact.email'),
            ],
        ]);
        return response()->json(['data' => $data]);
    }
}
