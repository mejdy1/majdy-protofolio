<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class TestimonialResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'author' => $this->author,
            'role' => $this->role,
            'company' => $this->company,
            'avatar_url' => $this->avatar_url,
            'quote' => $this->quote,
            'rating' => $this->rating,
            'featured' => $this->featured,
            'letter_url' => $this->letter_path ? Storage::disk('public')->url($this->letter_path) : null,
        ];
    }
}
