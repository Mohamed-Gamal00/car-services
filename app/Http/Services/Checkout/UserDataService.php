<?php

namespace App\Http\Services\Checkout;

use Illuminate\Support\Facades\Log;

/**
 * Service for saving user data (cars and addresses)
 */
class UserDataService
{
    /**
     * Save car details if requested
     */
    public function saveCarDetails($request, $user): void
    {
        if ($request->save_car_details != true) {
            return;
        }

        try {
            $user->cars()->syncWithoutDetaching([
                $request->car_id => [
                    'car_model' => $request->car_model,
                    'car_number' => $request->car_number,
                ]
            ]);

            Log::info('Car details saved', [
                'user_id' => $user->id,
                'car_id' => $request->car_id
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to save car details', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Save address details if requested
     */
    public function saveAddressDetails($request, $user): void
    {
        if (!$request->save_address_details) {
            return;
        }

        try {
            $existingAddress = $user->addresses()->where([
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'address' => $request->location,
            ])->first();

            if (!$existingAddress) {
                $user->addresses()->create([
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                    'address' => $request->location,
                    'user_id' => $user->id,
                ]);

                Log::info('Address saved', ['user_id' => $user->id]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to save address', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}
