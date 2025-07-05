<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'order_number' => $this->number,
            'payment_status' => $this->payment_status,
            'payment_method' => $this->payment_method ?? 'not found',
            'service' => $this->product_id,
            'car_name' => $this->car->getCurrentNameLangAttribute(),
            'car_model' => $this->car_model,
            'car_number' => $this->car_number,
            'total_price' => $this->total_price,
            'date' => $this->booking_date ?? '0000-00-00',
            'time' => $this->booking_time ?? '00-00',
            'location' => $this->location,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'description' => $this->note,
//            'rating_skipped' => $this->rating_skipped == 0 ? null : true,
            'is_arrived' => $this->is_arrived == 0 ? false : true, // captain arrived
            'rating' => $this->rating ? true : false,
            'invoice_url' => $this->invoice_url ? asset("storage/" . $this->invoice_url) : '-',
            'service_name' => $this->getServices(),
            'Package_info' => $this->getPackage() ?? 'N/A',
            'order-status' => $this->orderStatus->getCurrentNameLangAttribute(),
            'order-status-id' => $this->order_status_id,
            'Additional-services' => $this->getOrderChoices(),
            'getOrderImages' => $this->getOrderImages(),
            'user-info' => [
                'first_name' => $this->user->first_name,
                'family_name' => $this->user->family_name,
                'phone' => $this->user->phone_number,
                'image' => $this->user->image_url,
            ],
            'user-package' => $this->getUserPackage() ?? 'N/A',
        ];
    }

    private function getServices()
    {
        return $this->products->map(function ($sevice) {
            return [
                'service' => $sevice->getCurrentNameLangAttribute(), // Assuming you have product_name in OrderItem
                'price' => $sevice->price,
            ];
        });
    }


    private function getUserPackage()
    {
        $package = $this->user->packages()
            ->where('status', 'active')
            ->first();

        if (!$package) {
            return null;
        }

        return [
            'remaining_washes' => $package->remaining_washes,
            'name' => $package->package->getCurrentNameLangAttribute() ?? "N/A",
            'start_date' => $package->start_date,
            'expiry_date' => $package->expiry_date,
        ];
    }


    private function getPackage()
    {
        if (!$this->userPackage || !$this->userPackage->package) {
            return null;
        }

        $package = $this->userPackage->package;

        return [
            'name' => $package->getCurrentNameLangAttribute(),
            'price' => $package->price,
            'wash_count' => $package->wash_count,
            'validity_in_days' => $package->validity_in_days,
            'description' => $package->description,
            'image' => $package->image_url,
            'icon' => $package->icon_url,
            'features' => $package->features->map(function ($feature) {
                return [
                    'title' => $feature->getCurrentNameLangAttribute(),
                ];
            }),
        ];
    }

    private function getOrderChoices()
    {
        return $this->choices->map(function ($item) {
            return [
                'name' => $item->getCurrentNameLangAttribute(),
                'price' => $item->service_price
            ];
        });
    }



    private function getOrderImages()
    {
        return $this->images->map(function ($item) {
            return [
                'image' => asset('storage/' . $item->image),
            ];
        });
    }
}

