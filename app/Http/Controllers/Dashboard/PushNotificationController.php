<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Jobs\SendPushNotification;
use App\Models\DeviceToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PushNotificationController extends Controller
{
    public function create()
    {
        return view('dashboard.settings.web_notification');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
        ], [
            'title.required' => 'ادخل العنوان',
            'description.required' => 'ادخل المحتوي',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInputs($request->all());
        }

        // Get Firebase credentials and access token
        $credentialsFilePath = public_path('json/test-notification-3f882-dedabd83f76e.json');
        if (!file_exists($credentialsFilePath)) {
            return response()->json(['error' => 'Firebase credentials file not found'], 404);
        }

        $credentials = json_decode(file_get_contents($credentialsFilePath), true);
        $accessToken = $this->generateAccessToken($credentials);

        if (!$accessToken) {
            return response()->json(['error' => 'Failed to retrieve access token'], 500);
        }

        // Send notification to topic "general"
        $topic = 'general';
        $projectId = $credentials['project_id'];

        $notification = [
            "message" => [
                "topic" => $topic,
                "notification" => [
                    "title" => $request->title,
                    "body" => $request->description,
                ],
                "data" => [
                    "click_action" => "FLUTTER_NOTIFICATION_CLICK",
                ]
            ]
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($notification));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $accessToken,
            'Content-Type: application/json',
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        return redirect()->back()->with(['success' => 'تم إرسال الإشعار بنجاح']);
    }

    private function generateAccessToken($credentials)
    {
        $tokenUrl = "https://oauth2.googleapis.com/token";
        $privateKey = $credentials['private_key'];
        $clientEmail = $credentials['client_email'];
        $now = time();

        $jwtPayload = [
            "iss" => $clientEmail,
            "sub" => $clientEmail,
            "aud" => $tokenUrl,
            "iat" => $now,
            "exp" => $now + 3600,
            "scope" => "https://www.googleapis.com/auth/firebase.messaging"
        ];

        $header = base64_encode(json_encode(["alg" => "RS256", "typ" => "JWT"]));
        $payload = base64_encode(json_encode($jwtPayload));
        $signature = '';
        openssl_sign("$header.$payload", $signature, $privateKey, OPENSSL_ALGO_SHA256);
        $jwt = "$header.$payload." . base64_encode($signature);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $tokenUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            "grant_type" => "urn:ietf:params:oauth:grant-type:jwt-bearer",
            "assertion" => $jwt,
        ]));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/x-www-form-urlencoded',
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        $tokenResponse = json_decode($response, true);

        return $tokenResponse['access_token'] ?? null;
    }
}
