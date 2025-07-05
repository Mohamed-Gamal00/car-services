<?php

namespace App\Http\Controllers\Api;

use App\Helper\ApiResponse;
use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Services\SMSGateways\moraSms;
use App\Http\Services\VerificationServices;
use App\Models\Captain;
use App\Models\DeviceToken;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class CaptainAuthController extends Controller
{
    use Helper;

    public $sms_service;
    public $quadate;

    public function __construct(VerificationServices $services, moraSms $moraSms)
    {
        $this->sms_service = $services;
        $this->quadate = $moraSms;
    }

    public function login(Request $request)
    {

        $loginUserData = $request->validate([
            'phone_number' => 'required|',
            'password' => 'required|min:6'
        ]);
        $user = Captain::where('phone_number', $loginUserData['phone_number'])->first();
        if (!$user || !Hash::check($loginUserData['password'], $user->password)) {
            return response()->json([
                'message' => 'Invalid Credentials'
            ], 401);
        }

        if ($user) {
            $token = $user->createToken('-AuthToken')->plainTextToken;
            $data = [
                'access_token' => $token,
                'is_client' => false,
            ];
            return ApiResponse::sendResponse(200, 'login successfully', $data);
        } else {
            return ApiResponse::sendResponse(401, 'Invalid Credentials');
        }
    }


    public function verifyCode(Request $request)
    {
        $verificationData = $request->validate([
            'user_id' => 'required',  // Capture the user_id from the frontend
            'code' => 'required',     // The OTP code sent via SMS
        ]);

        $user = Captain::find($verificationData['user_id']);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $isValidCode = $this->sms_service->checkOTPCodePassword($user->id, $verificationData['code']);

        if ($isValidCode) {
            $token = $user->createToken('-AuthToken')->plainTextToken;
            return response()->json([
                'message' => 'Verification successful',
                'access_token' => $token,
            ]);
        } else {
            return response()->json(['message' => 'Invalid verification code'], 401);
        }
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return response()->json([
            "message" => "logged out"
        ]);
    }


//    public function registerToken(Request $request)
//    {
//
////        return $request->user()->tokens();
//
//        $validator = validator()->make($request->all(), [
//            'token' => 'required',   // device token
//            'type' => 'required|in:android,ios',
//        ]);
//
//        if ($validator->fails()) {
//            {
//                $response = [
//                    'status' => 0,
//                    'message' => $validator->errors()->first(),
//                    'data' => $validator->errors(),
//                ];
//                return response()->json($response);
//            }
//        }
//        Token::where('token', $request->token)->delete();
////        $request->user()->tokens()->create($request->all());
//        DeviceToken::create([
//            'token' => $request->token,
//            'type' => $request->type,
//            'captain_id' => $request->user()->id,
//
//        ]);
//        {
//            $response = [
//                'status' => 1,
//                'message' => 'تم التسجيل بنجاح',
//                'data' => null,
//            ];
//            return response()->json($response);
//        }
//
//    }

    public function registerToken(Request $request)
    {
        // Validate the incoming request
        $validator = validator()->make($request->all(), [
            'token' => 'required',   // Device token
            'type' => 'nullable|in:android,ios',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'message' => $validator->errors()->first(),
                'data' => $validator->errors(),
            ]);
        }

        // Delete any existing tokens with the same token value for other captains
        DeviceToken::where('token', $request->token)
            ->where('captain_id', '!=', $request->user()->id)
            ->delete();

        // Update or create a token for the authenticated captain
        DeviceToken::updateOrCreate(
            ['captain_id' => $request->user()->id], // Find by captain ID
            [
                'token' => $request->token,          // Update the token
                'type' => $request->type,           // Update type if provided
            ]
        );

        return response()->json([
            'status' => 1,
            'message' => 'تم التسجيل بنجاح',
            'data' => null,
        ]);
    }

}
