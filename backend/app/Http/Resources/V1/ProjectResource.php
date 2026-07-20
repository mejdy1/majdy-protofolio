<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $detail = $request->routeIs('*.show') || $request->boolean('full');
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'summary' => $this->summary,
            'cover_url' => $this->cover_url,
            'stack' => $this->stack ?? [],
            'year' => $this->year,
            'featured' => $this->featured,
            'client' => $this->client,
            'role' => $this->role,
            'live_url' => $this->live_url,
            'repo_url' => $this->repo_url,
            $this->mergeWhen($detail, [
                'body' => $this->body,
                'problem' => $this->problem,
                'solution' => $this->solution,
                'gallery' => $this->gallery ?? [],
            ]),
        ];
    }
}
