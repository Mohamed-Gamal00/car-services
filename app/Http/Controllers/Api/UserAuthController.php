<?php

namespace App\Http\Controllers\Api;

use App\Helper\ApiResponse;
use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\RegisterRequest;
use App\Http\Services\SMSGateways\moraSms;
use App\Http\Services\VerificationServices;
use App\Mail\PasswordResetCodeMail;
use App\Models\Captain;
use App\Models\DeviceToken;
use App\Models\ForgetPassword;
use App\Models\Token;
use App\Models\User;
use App\Models\User_verfication;
use App\Models\UserAddress;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Actions\CompletePasswordReset;
use Laravel\Fortify\Contracts\FailedPasswordResetLinkRequestResponse;
use Laravel\Fortify\Contracts\FailedPasswordResetResponse;
use Laravel\Fortify\Contracts\PasswordResetResponse;
use Laravel\Fortify\Contracts\ResetsUserPasswords;
use Laravel\Fortify\Contracts\SuccessfulPasswordResetLinkRequestResponse;
use Laravel\Fortify\Fortify;

use Illuminate\Support\Str;


class UserAuthController extends Controller
{
    public $sms_service;
    public $moraGateway;

    public function __construct(VerificationServices $services, moraSms $moraSms)
    {
        $this->sms_service = $services;
        $this->moraGateway = $moraSms;
    }

    use Helper;

    public function register(Request $request)
    {
        // Validation for the request
        $validate = Validator::make($request->all(), [
            'first_name' => 'required|string|max:250',
            'family_name' => 'nullable',
            'address' => 'nullable',
            'email' => 'nullable',
            'phone' => [
                'required',
                'regex:/^05\d{8}$/',
                'unique:users,phone'
            ],
            'password' => 'required|string|min:6|confirmed'
        ]);


        if ($validate->fails()) {
            return response()->json([
                'status' => 'failed',
                'message' => $validate->errors()->first(),
                'data' => $validate->errors(),
            ], 403);
        }


        // Declare the user variable outside the transaction
        $user = null;

        try {
            DB::transaction(function () use ($request, &$user) {
                // Create the user
                $user = User::create([
                    'name' => $request->first_name . $request->family_name,
                    'address' => $request->address ?? null,
                    'email' => $request->email ?? null,
                    'phone' => $request->phone,
                    'password' => Hash::make($request->password),
                ]);
            });

            // Generate the verification code and SMS
            $verificationData = $this->sms_service->setVerificationCode($user->id);
            //            return $verificationData;
            $message = $this->sms_service->getSMSVerifyMessageByAppName($verificationData->code);
            $smsSent = $this->moraGateway->sendSms($user->phone, $message);

            //            $smsSent = true;

            if ($smsSent) {
                return response()->json([
                    'message' => "Verification code sent to your phone number" . $smsSent['data']['message'],
                    'verification_required' => true,
                    'user_id' => $user->id,  // Send back user id for further verification process
                ]);
            } else {
                return response()->json([
                    'message' => 'Failed to send verification SMS. Please try again.',
                ], 500);
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => 'An Error Occurred While Creating Account',
            ], 500);
        }
    }


    public function login(Request $request)
    {
        $loginUserData = $request->validate([
            'phone' => 'required',
            'password' => 'required|min:6'
        ]);

        // Try to find the user in the User model
        $user = User::where('phone', $loginUserData['phone'])->first();

        if ($user && Hash::check($loginUserData['password'], $user->password)) {

            // Check if the user is verified
            if (!$user->verificationCode || !$user->verificationCode->is_verified) {
                // Generate a new verification code and send SMS
                $verificationData = $this->sms_service->setVerificationCode($user->id);
                $message = $this->sms_service->getSMSVerifyMessageByAppName($verificationData->code);
                $this->moraGateway->sendSms($user->phone, $message);

                return response()->json([
                    'message' => "Your account is not verified. A verification code has been sent to your phone number.",
                    'verification_required' => true,
                    'user_id' => $user->id,
                ], 403);
            }
            // If user found and password is correct
            $token = $user->createToken('-AuthToken')->plainTextToken;
            $data = [
                'access_token' => $token,
                'is_client' => true,
            ];
            return ApiResponse::sendResponse(200, 'Login successfully', $data);
        }

        // If user not found, try Captain model
        $captain = Captain::where('phone', $loginUserData['phone'])->first();

        if ($captain && Hash::check($loginUserData['password'], $captain->password)) {
            // If captain found and password is correct
            $token = $captain->createToken('-AuthToken')->plainTextToken;
            $data = [
                'access_token' => $token,
                'is_client' => false,
            ];
            return ApiResponse::sendResponse(200, 'Login successfully', $data);
        }

        // If no valid user or captain found
        return response()->json(['message' => 'Invalid Credentials'], 401);
    }


    public function verifyCode(Request $request)
    {
        $verificationData = $request->validate([
            'user_id' => 'required',  // Capture the user_id from the frontend
            'code' => 'required',     // The OTP code sent via SMS
        ]);

        // Check if user exists in User model
        $user = User::find($verificationData['user_id']);

        if (!$user) {
            // If user not found, check Captain model
            $user = Captain::find($verificationData['user_id']);
        }

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }


        $user_verificationCode = User_verfication::where('user_id', $user->id)->first();
        if ($user_verificationCode->verification_code_expires_at && now()->greaterThan($user_verificationCode->verification_code_expires_at)) {
            return ApiResponse::sendResponse(403, "انتهت صلاحية رمز التحقق. يرجى طلب واحد جديدة.");
        }
        // Validate the OTP code
        $isValidCode = $this->sms_service->checkOTPCodePassword($user->id, $verificationData['code']);

        if ($isValidCode) {
            // Update the is_verified field in the verification table
            User_verfication::where('user_id', $user->id)
                ->where('code', $verificationData['code']) // Ensure it updates the correct code
                ->update(['is_verified' => true]);

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


    public function forgetPassword(Request $request)
    {
        $user = User::where('phone', $request->phone)->first();
        if (!$user) {
            return ApiResponse::sendResponse(200, 'this phone not exist');
        }

        // Generate the verification code and SMS
        $verificationData = $this->sms_service->setVerificationCode($user->id);
        $message = $this->sms_service->getSMSVerifyMessageByAppName($verificationData->code);
        $smsSent = $this->moraGateway->sendSms($user->phone, $message);

        //        $smsSent = true;

        if ($smsSent) {
            // return $smsSent['data']['message'];
            return response()->json([
                'message' => 'تم اراسل كود التحقق الخاص بك'. $smsSent['data']['message'],
                'verification_required' => true,
                'user_id' => $user->id,  // Send back user id for further verification process
            ]);
        } else {
            return response()->json([
                'message' => 'Failed to send verification SMS. Please try again.',
            ], 500);
        }
        //        $uuid = Str::uuid()->toString();
        //        $code = Str::random(6); // Generates a random 6-character string
        //
        //        ForgetPassword::create([
        //            'uuid' => $uuid,
        //            'user_id' => $user->id,
        //            'code' => $code,
        //        ]);

        //        return ApiResponse::sendResponse(200, 'Reset code sent to your phone.', $code);
    }

    public function resetPassword(Request $request)
    {
        $user = auth()->user();
        $request->validate(['password' => 'required|string|min:6|confirmed']);
        $user->update(['password' => $request->password]);
        return response()->json(['message' => 'Password reset successfully.'], 200);
    }

    public function registerToken(Request $request)
    {
        // Validate the request
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

        // Check if the token exists for the user and update it, or create a new one
        DeviceToken::updateOrCreate(
            ['user_id' => $request->user()->id], // Find by user ID
            [
                'token' => $request->token,       // Update the token
                'type' => $request->type,        // Update type if provided
            ]
        );

        return response()->json([
            'status' => 1,
            'message' => 'تم التسجيل بنجاح',
            'data' => null,
        ]);
    }

    /**
     * Get the broker to be used during password reset.
     *
     */
    protected function broker(): PasswordBroker
    {
        return Password::broker(config('fortify.passwords'));
    }
}
