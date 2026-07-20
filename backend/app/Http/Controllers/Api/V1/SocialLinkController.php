<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\SocialLinkResource;
use App\Models\SocialLink;
use Illuminate\Support\Facades\Cache;

class SocialLinkController extends Controller
{
    public function index()
    {
        $data = Cache::flexible('api:social-links', [60, 600], fn () =>
            SocialLinkResource::collection(SocialLink::query()->ordered()->get())->resolve()
        );
        return response()->json(['data' => $data]);
    }
}
