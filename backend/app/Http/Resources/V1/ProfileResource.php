<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ProfileResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'headline' => $this->headline,
            'tagline' => $this->tagline,
            'bio' => $this->bio,
            'location' => $this->location,
            'email' => $this->email,
            'phone' => $this->phone,
            // Prefer an uploaded file (path) over the legacy URL column.
            'avatar_url' => $this->avatar_path ? Storage::disk('public')->url($this->avatar_path) : $this->avatar_url,
            'hero_image_url' => $this->hero_image_path ? Storage::disk('public')->url($this->hero_image_path) : $this->hero_image_url,
            'resume_url' => $this->resume_path ? Storage::disk('public')->url($this->resume_path) : $this->resume_url,
            'available_for_hire' => $this->available_for_hire,
            'roles' => $this->roles ?? [],
        ];
    }
}
