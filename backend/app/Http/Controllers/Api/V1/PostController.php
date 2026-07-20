<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->string('category')->toString();
        $page = (int) $request->integer('page', 1);
        $key = "api:posts:c={$category}:p={$page}";

        $payload = Cache::flexible($key, [60, 600], function () use ($category) {
            $q = Post::query()->with('category')->published()->orderByDesc('published_at');
            if ($category !== '') {
                $q->whereHas('category', fn ($cq) => $cq->where('slug', $category));
            }
            $paginator = $q->paginate(9);
            return [
                'data' => PostResource::collection($paginator->getCollection())->resolve(),
                'meta' => [
                    'current_page' => $paginator->currentPage(),
                    'last_page'    => $paginator->lastPage(),
                    'total'        => $paginator->total(),
                ],
            ];
        });
        return response()->json($payload);
    }

    public function show(string $slug)
    {
        $data = Cache::flexible("api:post:{$slug}", [60, 600], function () use ($slug) {
            $post = Post::query()->with('category')->published()->where('slug', $slug)->firstOrFail();
            return (new PostResource($post))->resolve(request());
        });
        return response()->json(['data' => $data]);
    }
}
