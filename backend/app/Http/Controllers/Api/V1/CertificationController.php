<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\CertificationResource;
use App\Models\Certification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CertificationController extends Controller
{
    public function index(Request $request)
    {
        $featured = $request->boolean('featured');
        $key = "api:certifications:f={$featured}";

        $data = Cache::flexible($key, [60, 600], function () use ($featured) {
            $q = Certification::query()->ordered();
            if ($featured) {
                $q->featured();
            }
            return CertificationResource::collection($q->get())->resolve();
        });

        return response()->json(['data' => $data]);
    }
}
