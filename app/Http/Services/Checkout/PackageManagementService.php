<?php

namespace App\Http\Services\Checkout;

use App\Models\UserPackage;
use Illuminate\Support\Facades\Log;

/**
 * Service for managing user packages
 */
class PackageManagementService
{
    /**
     * Validate user package
     */
    public function validateUserPackage($request, $user): UserPackage
    {
        $userPackage = UserPackage::where('package_id', $request->user_package_id)
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->first();

        if (!$userPackage) {
            throw new \Exception(__('general.The_package_is_invalid_or_has_been_fully_consumed'), 422);
        }

        // Check if expired
        if ($userPackage->expiry_date < now()) {
            $userPackage->update(['status' => 'expired']);
            throw new \Exception(__('general.The_package_has_expired'), 422);
        }

        // Check if consumed
        if ($userPackage->remaining_washes <= 0) {
            $userPackage->update(['status' => 'used_up']);
            throw new \Exception(__('general.The_package_is_invalid_or_has_been_fully_consumed'), 400);
        }

        return $userPackage;
    }

    /**
     * Decrement package usage
     */
    public function decrementPackageUsage(UserPackage $userPackage): void
    {
        $userPackage->decrement('remaining_washes');
        $userPackage->refresh();

        // Update status if fully consumed
        if ($userPackage->remaining_washes <= 0) {
            $userPackage->update(['status' => 'used_up']);
            Log::info('Package fully consumed', ['user_package_id' => $userPackage->id]);
        }

        // Check if expired
        if (now()->gt($userPackage->expiry_date)) {
            $userPackage->update(['status' => 'expired']);
            Log::info('Package expired', ['user_package_id' => $userPackage->id]);
        }
    }
}
