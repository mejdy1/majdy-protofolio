<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $detail = $request->routeIs('*.show');
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'excerpt' => $this->excerpt,
            'cover_url' => $this->cover_url,
            'reading_time' => $this->reading_time,
            'published_at' => $this->published_at?->toIso8601String(),
            'category' => $this->whenLoaded('category', fn () => [
                'name' => $this->category?->name,
                'slug' => $this->category?->slug,
                'color' => $this->category?->color,
            ]),
            $this->mergeWhen($detail, [
                'body' => $this->body,
            ]),
        ];
    }
}
