<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExperienceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'role' => $this->role,
            'company' => $this->company,
            'company_url' => $this->company_url,
            'location' => $this->location,
            'start_date' => $this->start_date?->toDateString(),
            'end_date' => $this->end_date?->toDateString(),
            'current' => $this->current,
            'description' => $this->description,
            'achievements' => $this->achievements ?? [],
            'stack' => $this->stack ?? [],
        ];
    }
}
