<?php

namespace App\Http\Controllers\Api;

use App\Helper\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\ViewPackageResource;
use App\Models\Package;
use App\Models\User;
use App\Models\UserPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PackageController extends Controller
{
    public function packages()
    {

        $packages = Package::where('is_active', '1')->get();

        if (count($packages) > 0) {
            return ApiResponse::sendResponse(200, ' Retrieved Successfully', ViewPackageResource::collection($packages));
        }
        return ApiResponse::sendResponse(200, 'No packages To Retrieved', []);
    }

    public function getPackage_id($package_id)
    {
        $package = Package::findOrFail($package_id);
        if ($package) {
            return ApiResponse::sendResponse(200, 'data Retrieved Successfully', new ViewPackageResource($package));
        }
        return ApiResponse::sendResponse(200, 'No package To Retrieved', []);
    }

    public function subscribe(Request $request)
    {
        $request->validate([
            'package_id' => 'required|exists:packages,id',
        ]);

        $package = Package::findOrFail($request->package_id);

        $userId = auth()->id();

        $activePackage = UserPackage::where('user_id', $userId)
            ->where('status', 'active')
            ->where('expiry_date', '>=', now())
            ->first();

        // لو عنده باقة نشطة بنفس الـ package_id اللي اختارها -> نرجع له رسالة
        if ($activePackage && $activePackage->package_id == $package->id) {
            return response()->json([
                'message' => __('general.You_already_have_an_active_package')
            ], 422);
        }
         //المستخدم عنده باقة نشطة وبيشترك في باقة مختلفةالقديمة تتلغي، والجديدة تتسجل
        if ($activePackage && $activePackage->package_id != $package->id) {
            $activePackage->update(['status' => 'canceled']);
        }

        $reference = Str::uuid();
        UserPackage::create([
            'user_id' => auth()->id(),
            'package_id' => $package->id,
            'remaining_washes' => $package->wash_count,
            'start_date' => now(),
            'expiry_date' => now()->addDays($package->validity_days),
            'status' => 'inactive',
            'reference' => $reference,
        ]);

        $paymentLink = route('user.payment_package', [
                'package_id' => $package->id,
                'method' => $request->payment_method,
            ]) . '?ref=' . $reference;
        return ApiResponse::sendResponse(200, 'success', $paymentLink);

    }

    public function RenewalSubscribe(Request $request)
    {
        $request->validate([
            'package_id' => 'required|exists:packages,id',
        ]);

        $package = Package::findOrFail($request->package_id);

        $userId = auth()->id();

        $currentActive = UserPackage::where('user_id', $userId)
            ->where('package_id', $package->id)
            ->where(function ($query) {
                $query->where('status', 'active')
                    ->orWhere('status', 'used_up')
                    ->orWhere('status', 'expired');
            })
            ->where('expiry_date', '>=', now())
            ->first();

        if (!$currentActive) {
            return response()->json([
                'message' => __('general.No_active_package_found_to_renew')
            ], 422);
        }

        $reference = Str::uuid();

        UserPackage::create([
            'user_id' => $userId,
            'package_id' => $package->id,
            'remaining_washes' => $package->wash_count,
            'start_date' => now(), // سيتم التحديث بعد الدفع
            'expiry_date' => now()->addDays($package->validity_days), // سيتم التحديث بعد الدفع
            'status' => 'inactive',
            'reference' => $reference,
        ]);

        $paymentLink = route('user.renewal_payment_package', [
                'package_id' => $package->id,
                'method' => $request->payment_method,
            ]) . '?ref=' . $reference;
        return ApiResponse::sendResponse(200, 'success', $paymentLink);

    }

}
