<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\ProjectResource;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $featured = $request->boolean('featured');
        $limit    = (int) $request->integer('limit', 0);
        $key = "api:projects:f={$featured}:l={$limit}";

        $data = Cache::flexible($key, [60, 600], function () use ($featured, $limit) {
            $q = Project::query()->published()->ordered();
            if ($featured) $q->featured();
            if ($limit > 0) $q->limit($limit);
            return ProjectResource::collection($q->get())->resolve();
        });
        return response()->json(['data' => $data]);
    }

    public function show(string $slug)
    {
        $data = Cache::flexible("api:project:{$slug}", [60, 600], function () use ($slug) {
            $project = Project::query()->published()->where('slug', $slug)->firstOrFail();
            return (new ProjectResource($project))->resolve(request());
        });
        return response()->json(['data' => $data]);
    }
}
