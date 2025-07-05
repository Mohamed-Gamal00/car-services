<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserPackageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'package_id' => $this->package_id,
            'package_name' => optional($this->package)->name,
            'remaining_washes' => $this->remaining_washes ?? 'n/a',
            'expiry_date' => $this->expiry_date ?? 'n/a',
            'status' => $this->status ?? 'n/a',
            'price' => $this->package->price ?? 'n/a',
            'icon' => $this->package->IconUrl,
            'image' => $this->package->image_url,

        ];
    }
}
