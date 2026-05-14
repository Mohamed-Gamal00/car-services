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
        $admins = \App\Models\Admin::select('id', 'name', 'email')->get();
        return view('dashboard.settings.web_notification', compact('admins'));
    }

    /**
     * Send test notification to current admin
     */
    public function sendToMe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
        ], [
            'title.required' => 'ادخل العنوان',
            'description.required' => 'ادخل المحتوي',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'بيانات غير صحيحة',
                'errors' => $validator->errors()
            ], 422);
        }

        $admin = auth()->guard('admin')->user();

        if (!$admin) {
            return response()->json([
                'success' => false,
                'message' => 'غير مصرح'
            ], 401);
        }

        // Get Firebase credentials and access token
        $credentialsFilePath = public_path('json/test-notification-3f882-dedabd83f76e.json');
        if (!file_exists($credentialsFilePath)) {
            return response()->json([
                'success' => false,
                'message' => 'ملف بيانات Firebase غير موجود'
            ], 404);
        }

        $credentials = json_decode(file_get_contents($credentialsFilePath), true);
        $accessToken = $this->generateAccessToken($credentials);

        if (!$accessToken) {
            return response()->json([
                'success' => false,
                'message' => 'فشل في الحصول على رمز الوصول'
            ], 500);
        }

        $projectId = $credentials['project_id'];
        $result = $this->sendToAdmin($admin->id, $request->title, $request->description, $accessToken, $projectId);

        return response()->json($result);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'target_type' => 'nullable|in:topic,admin,all_admins',
            'admin_id' => 'nullable|required_if:target_type,admin|exists:admins,id',
        ], [
            'title.required' => 'ادخل العنوان',
            'description.required' => 'ادخل المحتوي',
            'admin_id.required_if' => 'يرجى اختيار المسؤول',
            'admin_id.exists' => 'المسؤول غير موجود',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInputs($request->all());
        }

        // Get Firebase credentials and access token
        $credentialsFilePath = public_path('json/test-notification-3f882-dedabd83f76e.json');
        if (!file_exists($credentialsFilePath)) {
            return redirect()->back()->with(['error' => 'ملف بيانات Firebase غير موجود']);
        }

        $credentials = json_decode(file_get_contents($credentialsFilePath), true);
        $accessToken = $this->generateAccessToken($credentials);

        if (!$accessToken) {
            return redirect()->back()->with(['error' => 'فشل في الحصول على رمز الوصول']);
        }

        $targetType = $request->target_type ?? 'topic';
        $projectId = $credentials['project_id'];

        try {
            if ($targetType === 'admin') {
                // Send to specific admin
                $result = $this->sendToAdmin($request->admin_id, $request->title, $request->description, $accessToken, $projectId);
            } elseif ($targetType === 'all_admins') {
                // Send to all admins
                $result = $this->sendToAllAdmins($request->title, $request->description, $accessToken, $projectId);
            } else {
                // Send to topic (default behavior)
                $result = $this->sendToTopic('general', $request->title, $request->description, $accessToken, $projectId);
            }

            if ($result['success']) {
                return redirect()->back()->with(['success' => $result['message']]);
            } else {
                return redirect()->back()->with(['error' => $result['message']]);
            }
        } catch (\Exception $e) {
            \Log::error('Push notification error: ' . $e->getMessage());
            return redirect()->back()->with(['error' => 'حدث خطأ أثناء إرسال الإشعار']);
        }
    }

    /**
     * Send notification to a specific admin's devices
     */
    private function sendToAdmin($adminId, $title, $body, $accessToken, $projectId)
    {
        $admin = \App\Models\Admin::find($adminId);
        
        if (!$admin) {
            return ['success' => false, 'message' => 'المسؤول غير موجود'];
        }

        $tokens = $admin->deviceTokens()->pluck('token')->toArray();

        if (empty($tokens)) {
            return ['success' => false, 'message' => 'لا توجد أجهزة مسجلة لهذا المسؤول'];
        }

        $successCount = 0;
        $failCount = 0;

        foreach ($tokens as $token) {
            $result = $this->sendToToken($token, $title, $body, $accessToken, $projectId);
            if ($result) {
                $successCount++;
            } else {
                $failCount++;
            }
        }

        $message = "تم إرسال الإشعار إلى {$successCount} جهاز";
        if ($failCount > 0) {
            $message .= " (فشل {$failCount})";
        }

        return ['success' => true, 'message' => $message];
    }

    /**
     * Send notification to all admins
     */
    private function sendToAllAdmins($title, $body, $accessToken, $projectId)
    {
        $tokens = DeviceToken::where('tokenable_type', \App\Models\Admin::class)
            ->pluck('token')
            ->toArray();

        if (empty($tokens)) {
            return ['success' => false, 'message' => 'لا توجد أجهزة مسجلة'];
        }

        $successCount = 0;
        $failCount = 0;

        foreach ($tokens as $token) {
            $result = $this->sendToToken($token, $title, $body, $accessToken, $projectId);
            if ($result) {
                $successCount++;
            } else {
                $failCount++;
            }
        }

        $message = "تم إرسال الإشعار إلى {$successCount} جهاز";
        if ($failCount > 0) {
            $message .= " (فشل {$failCount})";
        }

        return ['success' => true, 'message' => $message];
    }

    /**
     * Send notification to a topic
     */
    private function sendToTopic($topic, $title, $body, $accessToken, $projectId)
    {
        $notification = [
            "message" => [
                "topic" => $topic,
                "notification" => [
                    "title" => $title,
                    "body" => $body,
                ],
                "data" => [
                    "click_action" => "FLUTTER_NOTIFICATION_CLICK",
                ]
            ]
        ];

        $result = $this->sendFCMRequest($notification, $accessToken, $projectId);
        
        if ($result) {
            return ['success' => true, 'message' => 'تم إرسال الإشعار بنجاح'];
        } else {
            return ['success' => false, 'message' => 'فشل إرسال الإشعار'];
        }
    }

    /**
     * Send notification to a specific device token
     */
    private function sendToToken($token, $title, $body, $accessToken, $projectId)
    {
        $notification = [
            "message" => [
                "token" => $token,
                "notification" => [
                    "title" => $title,
                    "body" => $body,
                ],
                "webpush" => [
                    "notification" => [
                        "icon" => asset('favicon.ico'),
                        "badge" => asset('favicon.ico'),
                    ],
                    "fcm_options" => [
                        "link" => url('/dashboard')
                    ]
                ]
            ]
        ];

        return $this->sendFCMRequest($notification, $accessToken, $projectId);
    }

    /**
     * Send FCM request
     */
    private function sendFCMRequest($notification, $accessToken, $projectId)
    {
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
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 200) {
            \Log::info('FCM notification sent successfully', ['response' => $response]);
            return true;
        } else {
            \Log::error('FCM notification failed', [
                'http_code' => $httpCode,
                'response' => $response
            ]);
            return false;
        }
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
