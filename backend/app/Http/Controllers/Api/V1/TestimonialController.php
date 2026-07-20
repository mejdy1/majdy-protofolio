<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\TestimonialResource;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class TestimonialController extends Controller
{
    public function index(Request $request)
    {
        $featured = $request->boolean('featured');
        $key = "api:testimonials:f={$featured}";

        $data = Cache::flexible($key, [60, 600], function () use ($featured) {
            $q = Testimonial::query()->ordered();
            if ($featured) $q->featured();
            return TestimonialResource::collection($q->get())->resolve();
        });
        return response()->json(['data' => $data]);
    }
}
