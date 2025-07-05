<?php

namespace App\Http\Services;

use App\Models\DiscountCode;
use Carbon\Carbon;

class DiscountHandler
{
    public function applyDiscount($discountCode, $serviceId, $orderTotalPrice, $userId)
    {
        $discount = DiscountCode::where('code', $discountCode)
            ->where('status', 'active')
            ->where('number_of_used', '>', 0)
            ->first();

        if (!$discount) {
            return [
                'status' => 'error',
                'message' => 'This discount code is either expired or invalid.'
            ];
        }

        $alreadyUsed = $discount->users()->where('user_id', $userId)->exists();
        if ($alreadyUsed) {
            return [
                'status' => 'error',
                'message' => 'You have already used this discount code.'
            ];
        }

        if ($discount->products()->exists()) {
            $validForService = $discount->products()
                ->where('products.id', $serviceId)
                ->exists();
            if (!$validForService) {
                return [
                    'status' => 'error',
                    'message' => 'This discount code is not valid for the selected service.'
                ];
            }
        }

        $discountAmount = 0;
        if ($discount->discount_type === 'percentage') {
            $discountAmount = ($discount->price / 100) * $orderTotalPrice;
        } elseif ($discount->discount_type === 'price') {
            $discountAmount = $discount->price;
        }

        $discountAmount = min($discountAmount, $orderTotalPrice);
        $finalPrice = $orderTotalPrice - $discountAmount;


        $discount->decrement('number_of_used');
        $discount->users()->attach($userId, ['used_at' => now()]);

        return [
            'status' => 'success',
            'message' => 'Discount applied successfully.',
//            'discount_amount' => $discountAmount,
//            'final_price' => $finalPrice,
            'discount_amount' =>number_format($discountAmount, 2, '.', ''),
            'final_price' =>number_format($finalPrice, 2, '.', ''),
        ];
    }


}
