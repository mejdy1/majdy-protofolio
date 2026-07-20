<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\ProfileResource;
use App\Models\Profile;
use Illuminate\Support\Facades\Cache;

class ProfileController extends Controller
{
    public function show()
    {
        $data = Cache::flexible('api:profile', [60, 600], fn () =>
            (new ProfileResource(Profile::current()))->resolve()
        );
        return response()->json(['data' => $data]);
    }
}
