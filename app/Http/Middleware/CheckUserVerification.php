<?php

namespace App\Http\Middleware;

use App\Http\Services\SMSGateways\moraSms;
use App\Http\Services\VerificationServices;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserVerification
{
    public $sms_service;
    public $moraGateway;

    public function __construct(VerificationServices $services, moraSms $moraSms)
    {
        $this->sms_service = $services;
        $this->moraGateway = $moraSms;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user(); // Get authenticated user
//        // Debugging: Return the user verification code
//        return response()->json([
//            'user' => $user,
//            'verificationCode' => $user->verificationCode->is_verified,
//        ]);
        // Debugging: Return the user verification code
        if (!$user->verificationCode) {
            $verificationData = $this->sms_service->setVerificationCode($user->id);
            $message = $this->sms_service->getSMSVerifyMessageByAppName($verificationData->code);
            $this->moraGateway->sendSms($user->phone, $message);
            return response()->json([
                'status' => "failed؛",
                'message' => "Your account is not verified. A new verification code has been sent to your phone number.",
                'user_id' => $user->id,
            ]);
        }
        // Check if the user is verified
        if ($user && $user->verificationCode && !$user->verificationCode->is_verified) {

            // Generate the verification code and SMS
            $verificationData = $this->sms_service->setVerificationCode($user->id);
//            return $verificationData;
            $message = $this->sms_service->getSMSVerifyMessageByAppName($verificationData->code);
            $smsSent = $this->moraGateway->sendSms($user->phone, $message);

//            $smsSent = true;

            if ($smsSent) {
                return response()->json([
                    'message' => "Your account is not verified. A new verification code has been sent to your phone number.",
                    'verification_required' => true,
                    'user_id' => $user->id,
                ], 403);
            } else {
                return response()->json([
                    'message' => 'Failed to send verification SMS. Please try again later.',
                ], 500);
            }

        }
        return $next($request);
    }
}
