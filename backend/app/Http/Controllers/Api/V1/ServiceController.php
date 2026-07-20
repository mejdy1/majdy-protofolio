<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\ServiceResource;
use App\Models\Service;
use Illuminate\Support\Facades\Cache;

class ServiceController extends Controller
{
    public function index()
    {
        $data = Cache::flexible('api:services', [60, 600], fn () =>
            ServiceResource::collection(Service::query()->ordered()->get())->resolve()
        );
        return response()->json(['data' => $data]);
    }

    public function show(string $slug)
    {
        $data = Cache::flexible("api:service:{$slug}", [60, 600], fn () =>
            (new ServiceResource(Service::query()->where('slug', $slug)->firstOrFail()))->resolve()
        );
        return response()->json(['data' => $data]);
    }
}
