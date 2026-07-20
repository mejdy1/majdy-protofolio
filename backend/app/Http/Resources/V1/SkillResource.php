<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SkillResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'icon' => $this->icon,
            'category' => $this->category,
            'proficiency' => $this->proficiency?->value,
            'proficiency_pct' => $this->proficiency?->percentage(),
            'years_experience' => $this->years_experience,
            'color' => $this->color,
        ];
    }
}
