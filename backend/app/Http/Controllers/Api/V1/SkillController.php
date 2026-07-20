<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\SkillResource;
use App\Models\Skill;
use Illuminate\Support\Facades\Cache;

class SkillController extends Controller
{
    public function index()
    {
        $grouped = Cache::flexible('api:skills', [60, 600], function () {
            $skills = Skill::query()->ordered()->get();
            return $skills->groupBy('category')->map(fn ($group, $key) => [
                'category' => $key,
                'items' => SkillResource::collection($group)->resolve(),
            ])->values()->all();
        });
        return response()->json(['data' => $grouped]);
    }
}
