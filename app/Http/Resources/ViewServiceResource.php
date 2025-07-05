<?php

namespace App\Http\Resources;

use App\currency\Currency;
use App\Helper\Helper;
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
        return [
            'id' => $this->id,
            'name' => $this->getCurrentNameLangAttribute(),
            'image' => $this->image_url,
            'price' => $this->price,
//            'product_status' => $this->availability->getCurrentNameLangAttribute(),
            'status' => $this->status,
            'description' => translateWithHTMLTags($this->description),
            'bg_image' => $this->BgImageUrl,
            'images' => $this->images->map(function ($image) {
                return [
                    'image_url' => $image->image_url,
                ];
            }),
            'Additional-services' => $this->choices->map(function ($choice) {
                return [
                    'id' => $choice->id,
                    'name' => $choice->getCurrentNameLangAttribute(),
                    'image' => $choice->image_url,
                    'price' => (int)$choice->service_price,
                ];
            }),
            'features-services' => $this->features->map(function ($feature) {
                return [
                    'id' => $feature->id,
                    'name' => $feature->getCurrentNameLangAttribute(),
                    'description' => translateWithHTMLTags($feature->feature_description),
                ];
            }),
        ];
    }
}