<?php

namespace App\Http\Controllers\Api;

use App\Helper\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePersonalInfoRequest;
use App\Http\Resources\CaptainInfoResource;
use App\Http\Resources\UserInfoResource;
use Illuminate\Http\Request;
use App\Helper\Helper;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class CaptainInfoController extends Controller
{
    use Helper;

    public function getCaptainInfo(Request $request)
    {
        $user = $request->user();
        return ApiResponse::sendResponse(200, 'Captain Personal Info Retrieved Successfully', CaptainInfoResource::make($user));
    }

    public function changePersonalInfo(ChangePersonalInfoRequest $request)
    {
        $request->validated();
        $user = $request->user();


        $user->update([
            'name' => $request->name ?? $user->name,
            'last_name' => $request->last_name ?? $user->last_name,
            'phone_number' => $request->phone_number ?? $user->phone_number,
        ]);

        $data = [
            'first_name' => $user->name,
            'family_name' => $user->name,
            'phone_number' => $user->phone_number,
        ];

        return ApiResponse::sendResponse(200, 'Personal Info Changed Successfully', $data);
    }

    public function changeProfileImage(Request $request)
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $user = $request->user();
//        return $user;

        $oldImage = $user->image;
        $newImage = $this->uploadedImage(request(), 'image', 'captains');

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

    public function changePassword(Request $request)
    {
        $request->validate([
            'password' => ['required', 'confirmed', 'min:8']
        ]);

        $user = $request->user();

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return ApiResponse::sendResponse(200, 'Password Changed Successfully', []);
    }
}
