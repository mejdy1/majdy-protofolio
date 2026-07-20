<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'summary' => $this->summary,
            'body' => $this->body,
            'icon' => $this->icon,
            'tier' => $this->tier?->value,
            'starting_price' => $this->starting_price,
            'features' => $this->features ?? [],
        ];
    }
}
