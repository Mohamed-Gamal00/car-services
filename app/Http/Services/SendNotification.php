<?php

namespace App\Http\Services;

use Google\Client as GoogleClient;
use Illuminate\Support\Facades\Http;

class SendNotification
{
    function notifyByFirebase($title, $body, $tokens, $data = [])
    {
//        return
//        $fcmTokens = [
//            'd4DH56SNQGeN3zxw4QVbji:APA91bHbHataFjqa2edsnQFS5QnehHYcBtkIQTBFIYJt1hcoFpOmp4UMM4sgfQ0NiJdBJNNHMBw3eFsep7DvpXD-lu3h2rWDULfLVGkVo8htQzDb0j0eTfQ',
//            'f8GjpaYjQZScjEkJjagYVD:APA91bFu2ngFH6xdsOv07b-C9ZxlM2wk0DG0UzqiQEE-a6_fG8sT4TbulKuJOwmKLBKBgPalcqemJDH6I7ZQ5yEEMd82rWT6OyDGr-GFQfmyDxwUKtn_JFo',
//            'fLVUD3-dTnymyrVtC6fZ7Q:APA91bE-YViucnXRnfmQAGCn4Nrr04kYqwtfvYteeFH_jkTQgOkWTgzFVxRu-vPAAv4xDdspf56jfvjAEHUKdgoLufYYe7FSzD-D6UojkNnwWnRLIS0lNAY',
//            'fqNdfpuhSdK-8oNoxoYMdP:APA91bGE9iQqHC7cgOZ1OEmWpgpWXs1MDla_p5NiJkus_5UpM1nJSV2Go8gyym2GdL9B5hP5V-mHH7GOPcGfv3b6a0benGO6SU4XVws2oz6lLRZQROCeW58',
//            'f6yRr7w8Qk-jl0r9fPIX7C:APA91bH7hX_-FbIkp1Pet_bdPhRNv_EMeDdwsXnP2iuiIeXefBjCbMby1h-XZ3TiFPV1V024bPRcFon7qPBoZSoechs72w9hq_WxvC3SQs-yRZH4UJEUC6Y',
//        ];
//        $title = 'notify';
//        $body = 'notifications';
        $data = [];
//        return $fcmTokens;
//        $title = "إشعار جديد";
//        $body = "تيست تيست تيست";
        $fcmTokens = $tokens;

        // Path to the service account JSON file
        $credentialsFilePath = Http::get(asset('json/test-notification-3f882-dedabd83f76e.json')); // in server
//        return $credentialsFilePath;

        // Set up Google Client
        $client = new GoogleClient();
        $client->setAuthConfig($credentialsFilePath);
        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
        $client->refreshTokenWithAssertion();
        $token = $client->getAccessToken();

        $access_token = $token['access_token'];

        $headers = [
            "Authorization: Bearer $access_token",
            'Content-Type: application/json'
        ];

        // Arrays to store results
        $successes = [];
        $errors = [];

        $fcmMsg = array(
            'body' => $body,
            'title' => $title,
        );
//        return $fcmTokens;
        foreach ($fcmTokens as $fcmToken) {
            $data = [
                "message" => [
                    "token" => $fcmToken,  // This won't be returned
                    "notification" => $fcmMsg,
                    "android" => [
                        "priority" => "high"
                    ],
                ]
            ];

            $payload = json_encode($data);

            // Initialize cURL
            $ch = curl_init();
            //curl_setopt(): Sets an option for the cURL transfer.
            //CURLOPT_URL: Specifies the URL to which the request will be sent. In your case, it’s the FCM endpoint for sending messages.
            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/v1/projects/quickclean-27487/messages:send');
            //CURLOPT_POST: Tells cURL to send the request using the POST method. This is essential when you need to send data (like JSON payloads) to the server.
            curl_setopt($ch, CURLOPT_POST, true);
            //CURLOPT_HTTPHEADER: Sets custom HTTP headers for the request. This is often used to send authorization tokens and specify the content type (e.g., application/json).
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            //CURLOPT_RETURNTRANSFER: If set to true, it tells cURL to return the response as a string instead of outputting it directly. This is useful for further processing of the response.
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            //CURLOPT_SSL_VERIFYPEER: When set to true, it enables verification of the peer's SSL certificate. This is important for security, ensuring that the connection is secure.
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
            //CURLOPT_POSTFIELDS: This option specifies the data to send in the POST request. In your case, it’s the JSON payload containing the notification details.
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            //CURLOPT_VERBOSE: If set to true, it makes cURL output detailed information about the transfer (useful for debugging).
            curl_setopt($ch, CURLOPT_VERBOSE, true);

            // Send the request
            $response = curl_exec($ch); //curl_exec(): Executes the cURL session. This sends the request to the specified URL and returns the response from the server.
            $err = curl_error($ch);     //curl_error(): Retrieves any error message that occurred during the last cURL operation. This is helpful for error handling.
            curl_close($ch);   //curl_close(): Closes the cURL session and frees up the resources associated with it. This is a good practice to prevent memory leaks.

            // Handle the response
            if ($err) {
                return response()->json([
                    'message' => 'Curl Error: ' . $err
                ], 500);
            } else {
                $decodedResponse = json_decode($response, true);
                if (isset($decodedResponse['error'])) {
                    if ($decodedResponse['error']['message'] === 'NotRegistered') {
                        // Remove the token from your database
                        $this->removeInvalidToken($fcmToken);
                    }
                }
            }
        }
        // Return all results in the response
        return response()->json([
            'message' => 'Notification process completed.',
            'successes' => $successes,
            'errors' => $errors
        ]);
    }

}