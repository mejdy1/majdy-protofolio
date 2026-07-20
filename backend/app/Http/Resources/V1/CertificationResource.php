<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class CertificationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'issuer' => $this->issuer,
            'issue_date' => $this->issue_date?->toDateString(),
            'expiry_date' => $this->expiry_date?->toDateString(),
            'credential_id' => $this->credential_id,
            'credential_url' => $this->credential_url,
            'file_url' => $this->file_path ? Storage::disk('public')->url($this->file_path) : null,
            'featured' => $this->featured,
        ];
    }
}
