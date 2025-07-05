<?php

namespace App\Http\Controllers\Api\Profile;

use App\Helper\ApiResponse;
use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePersonalInfoRequest;
use App\Http\Resources\UserAddressesResource;
use App\Http\Resources\UserCarsResource;
use App\Http\Resources\UserInfoResource;
use App\Http\Resources\UserPackageResource;
use App\Models\User;
use App\Models\UserPackage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PersonalInfoController extends Controller
{
    use Helper;

    public function testauth()
    {
        return 'test';
    }

    public function changePersonalInfo(ChangePersonalInfoRequest $request)
    {
        $request->validated();
        $user = $request->user();


        $user->update([
            'first_name' => $request->first_name ?? $user->first_name,
            'family_name' => $request->last_name ?? $user->family_name,
            'phone_number' => $request->phone_number ?? $user->phone_number,
//            'email' => $request->email ?? $user->email
        ]);

        $data = [
            'first_name' => $user->first_name,
            'family_name' => $user->family_name,
            'phone_number' => $user->phone_number,
//            'email' => $user->email
        ];

        return ApiResponse::sendResponse(200, 'Personal Info Changed Successfully', $data);
    }


    public function changeProfileImage(Request $request)
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $user = $request->user();

        $oldImage = $user->image;
        $newImage = $this->uploadedImage(request(), 'image', 'users');

        if ($newImage) {
            $image = $newImage;
        }

        if ($newImage && $oldImage) {
            Storage::disk('public')->delete($oldImage);
        }

        if ($request->image) {
            $user->update([
                'image' => $image
            ]);
        }

        $data = [
            'image' => $user->image_url
        ];

        return ApiResponse::sendResponse(200, 'Profile Image Updated Successfully', $data);
    }

    public function getUserInfo(Request $request)
    {
        $user = $request->user();

        return ApiResponse::sendResponse(200, 'User Personal Info Retrieved Successfully', UserInfoResource::make($user));
    }


    public function getUserCars(Request $request)
    {
        $user = $request->user();
//        return $user->cars;

        if ($user->cars->isEmpty()) {
            return response()->json(['message' => 'No cars found'], 404);
        }
        return ApiResponse::sendResponse(200, 'cars Retrieved Successfully', UserCarsResource::collection($user->cars));
    }

    public function deleteUserCar(Request $request, $carId)
    {
        $user = $request->user();
//        return $user;
        // Check if the car exists in the user's cars
        $car = $user->cars()->where('car_id', $carId)->first();

        if (!$car) {
            return response()->json(['message' => 'Car not found for the user'], 404);
        }

        // Detach the car from the user
        $user->cars()->detach($carId);

        return response()->json(['message' => 'Car removed successfully']);
    }

    public function changePassword(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'confirmed', 'min:8']
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return ApiResponse::sendResponse(403, 'Current password is incorrect', []);
        }

        try {
            $user->update([
                'password' => Hash::make($request->password)
            ]);

            Log::info("Password successfully changed for user ID");

            return ApiResponse::sendResponse(200, 'Password changed successfully', []);

        } catch (\Exception $e) {
            Log::error("Failed to change password for user ID. Error: {$e->getMessage()}");
            return ApiResponse::sendResponse(500, 'An error occurred while changing the password', []);
        }
    }

    public function getUserPackage(Request $request)
    {
        $user = $request->user();

        $activePackage = $user->activePackage();


        if (!$activePackage) {
            return ApiResponse::sendResponse(404, 'No active package found');
        }

        return ApiResponse::sendResponse(200, 'Active package retrieved successfully', UserPackageResource::make($activePackage));
    }

    protected function sendNotification(User $user, $title, $body)
    {
        $deviceTokens = $user->devicetokens()->pluck('token');
        if ($deviceTokens->isEmpty()) return;

        app()->setLocale($user->lang ?? 'ar');

        $data = [
            'type' => 'package',
            'action' => 'renewal_reminder'
        ];

        // استخدم نفس ميثود إرسال فايربيز
        $this->notifyByFirebase($title, $body, $deviceTokens->toArray(), $data);
    }

}
