<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\EducationResource;
use App\Http\Resources\V1\ExperienceResource;
use App\Models\Education;
use App\Models\Experience;
use Illuminate\Support\Facades\Cache;

class ResumeController extends Controller
{
    public function experience()
    {
        $data = Cache::flexible('api:experience', [60, 600], fn () =>
            ExperienceResource::collection(Experience::query()->ordered()->get())->resolve()
        );
        return response()->json(['data' => $data]);
    }

    public function education()
    {
        $data = Cache::flexible('api:education', [60, 600], fn () =>
            EducationResource::collection(Education::query()->ordered()->get())->resolve()
        );
        return response()->json(['data' => $data]);
    }
}
