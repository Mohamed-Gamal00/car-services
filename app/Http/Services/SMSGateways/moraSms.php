<?php

namespace App\Http\Services\SMSGateways;

use App\Models\Setting;
use GuzzleHttp\Client;
use Exception;

class moraSms
{
    public $client;

    public function __construct()
    {
        if (!$this->client) {
            $this->client = new Client();
        }
    }

    // public function sendSms($phone, $message, $language = 'en', $model = null)
    // {
    //     $setting = Setting::first();

    //     try {
    //         // API credentials
    //         $apiKey = $setting->sms_api_key; // Mora API key
    //         $username = $setting->sms_user_name; // Your username
    //         $sender = $setting->sernder; // Sender name (owner name)
    //         $phone_number = $phone; // phone of user who will receive sms
    //         $sms_content = urlencode($message); //message

    //         // Construct the Mora API URL
    //         $url = "https://www.mora-sa.com/api/v1/sendsms?api_key=$apiKey&username=$username&message=$sms_content&numbers=$phone_number&sender=$sender&unicode=e&return=json";

    //         // Send the GET request to the Mora API
    //         $response = $this->client->get($url);

    //         // Get the response body
    //         $content = $response->getBody()->getContents();

    //         $jsonResponse = json_decode($content, true);

    //         if (isset($jsonResponse['status']['code']) && $jsonResponse['status']['code'] == 200) {
    //             return true;
    //         } else {
    //             info("Mora API error status or unexpected response!");
    //             return false;
    //         }


    //     } catch (Exception $e) {
    //         // Log the exception message if the API call fails
    //         info("Mora API failed to send SMS to $phone: " . $e->getMessage());
    //         return false;
    //     }
    // }

    public function sendSms($phone, $message, $language = 'en', $model = null)
    {
        try {
            // مؤقتًا بدون integration مع SMS Provider
            // Static response دائمًا success لتجربة النظام فقط

            info("Fake SMS Sent Successfully", [
                'phone' => $phone,
                'message' => $message,
            ]);

            return [
                'status' => [
                    'code' => 200,
                    'message' => 'SMS sent successfully (mock response)'
                ],
                'data' => [
                    'phone' => $phone,
                    'message' => $message
                ]
            ];
        } catch (\Exception $e) {
            info("Mock SMS failed: " . $e->getMessage());

            return [
                'status' => [
                    'code' => 500,
                    'message' => 'Mock SMS failed'
                ]
            ];
        }
    }
}
