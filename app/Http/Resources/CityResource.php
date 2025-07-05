<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CityResource extends JsonResource
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
            'status' => $this->status === 'not_used' ? 'غير مفعل' : 'مفعل',
            'polygon_coordinates' => json_decode($this->polygon_coordinates, true),
//            'southWest' => [
//                'latitude' => $this->latitude,
//                'longitude' => $this->longitude,
//            ],
//            'northEast' => [
//                'latitude' => $this->end_latitude,
//                'longitude' => $this->end_longitude,
//            ],
        ];
    }
}
