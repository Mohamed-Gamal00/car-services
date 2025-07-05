<?php

namespace App\Http\Controllers;


use App\Models\User_verfication;
use Illuminate\Http\Request;
use App\Http\Services\VerificationServices;
use App\Http\Services\SMSGateways\moraSms;
use Illuminate\Support\Facades\Auth;

class SendMessageController extends Controller
{
    public $sms_service;
    public $quadate;

    public function __construct(VerificationServices $services, moraSms $moraSms)
    {
        $this->sms_service = $services;
        $this->quadate = $moraSms;
    }

    public function sendSmS(Request $request)
    {
        $user = $request->user();
        $verfication_data = $this->sms_service->setVerificationCode($user->id);
        $message = $this->sms_service->getSMSVerifyMessageByAppName($verfication_data->code);
//        $result = $this->quadate->sendSms($user->phone_number, $message);
        $result = true;
        if ($result) {
            return "SMS sent successfully to $user->phone_number with message: $message";
        } else {
            return "Failed to send SMS to $user->phone_number";
        }
    }
}
