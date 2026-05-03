<?php

namespace App\Http\Resources;

use App\Helper\Helper;
use App\Models\City;
use App\Models\Country;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserInfoResource extends JsonResource
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
            'is_client' => true,
            'profile_image' => $this->image_url ?? null,
            'name' => $this->name,
            'phone_number' => $this->phone,
            'package' => $this->getActivePackageData(),
            'Record_washes' => $this->getRecord_washes(),

        ];
    }

    private function getActivePackageData()
    {
        $package = $this->activePackage(); // Assuming this is defined in the model

        if (!$package) {
            return null;
        }

        return [
            'package_id' => $package->package_id,
            'package_name' => optional($package->package)->current_name_lang, // fixed here
            'start_date' => $package->start_date,
            'expires_at' => $package->expiry_date,
            'icon'          => $package->package?->IconUrl,
            'price' => optional($package->package)->price,
            'remaining_services' => $package->remaining_washes,
            'status' => $package->status,
        ];
    }


    private function getRecord_washes()
    {
        $userPackage = $this->activePackage();
        if (!$userPackage) {
            return null;
        }

        $orders = Order::with('userPackage.package')
        ->where('user_package_id', $userPackage->id)
            ->where('user_id', auth()->id())
            ->get();

        return $orders->map(function ($order) {
            $package = $order->userPackage?->package;

            return [
                'created_at'    => $order->created_at,
                'booking_date'  => $order->booking_date,
                'booking_time'  => $order->booking_time,
                'location'      => $order->location,
                'icon'          => $package?->IconUrl,
                'image'         => $package?->image_url,
            ];
        });
    }

}