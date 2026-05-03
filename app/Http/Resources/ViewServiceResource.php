<?php

namespace App\Http\Resources;

use App\Helper\Helper;
use App\Models\Choice;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ViewServiceResource extends JsonResource
{
    use Helper;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Get all available choices (additional services)
        $allChoices = Choice::all();

        return [
            'id' => $this->id,
            'name' => $this->getCurrentNameAttribute(),
            'image' => $this->image_url,
            'price' => $this->price,
            'is_active' => $this->is_active,
            'description' => translateWithHTMLTags($this->description),
        ];
    }
}
