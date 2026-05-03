<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ViewPackageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->getCurrentNameLangAttribute(),
            'description' => translateWithHTMLTags($this->description),
            'icon' => $this->IconUrl,
            'wash_count' => $this->wash_count,
            'price' => $this->price,
            'image' => $this->image_url,
            'validity_in_days' => $this->validity_days,
            'is_active' => $this->is_active=='1' ? 'active' : 'not active',
            'package-features' => $this->features->map(function ($feature) {
                return [
                    'id' => $feature->id,
                    'title' => $feature->getCurrentNameLangAttribute(),
                ];
            }),
        ];
    }
}
