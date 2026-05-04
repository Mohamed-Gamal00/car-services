<?php

namespace App\Helper;

use App\Models\DeviceToken;
use App\Models\Guest;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Mpdf\Mpdf;

/*notification*/

use Google\Client as GoogleClient;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait Helper
{
    public function uploadedImage($request, $fileName, $dirName)
    {
        // if request hasn't it will make this method return null and if not it will return the path
        if (!$request->hasFile($fileName)) {
            return null;
        }

        $file = $request->file($fileName); // return uploadedFile object
        $path = $file->store('uploads/' . $dirName, [
            'disk' => 'public'
        ]);
        return $path;
    }

    public function uploadedLogo($request, $fileName, $dirName)
    {
        // if request hasn't it will make this method return null and if not it will return the path
        if (!$request->hasFile('logo')) {
            return;
        }

        $file = $request->file($fileName); // return uploadedFile object
        $path = $file->store('uploads/' . $dirName, [
            'disk' => 'public'
        ]); // or i can put key and value ('disk' => 'public')
        return $path;
    }

    public function checkIfProductExists(Request $request, $productId = null)
    {
//        $userId = $request->header('userId');

        $userId = $request->user()->id ?? $request->header('user-id');
        $guestId = $request->header('guest-id');

        if ($userId) {
            $user = User::findOrFail($userId);
            $product = $user->wishlistProducts->where('id', $productId)->first();
            if ($product) {
                return true;
            }
            return false;
        }


        if ($guestId) {
            $guest = Guest::find($guestId);
            $product = $guest->wishlistProducts->where('id', $productId)->first();
            if ($product) {
                return true;
            }
            return false;

        }
        return false;
    }


    function notifyByFirebase($title, $body, $tokens, $data = [])
    {
        try {
            Log::info('start send notification');

            // ملف بيانات الخدمة
            $credentialsFilePath = public_path('json/test-notification-3f882-dedabd83f76e.json');

            if (!file_exists($credentialsFilePath)) {
                Log::error('Firebase credentials file not found.', ['path' => $credentialsFilePath]);
                return;
            }

            $credentials = json_decode(file_get_contents($credentialsFilePath), true);
            $accessToken = $this->generateAccessToken($credentials);

            if (!$accessToken) {
                Log::error('Failed to retrieve access token');
                return;
            }

            $projectId = $credentials['project_id'];
            $fcmMsg = [
                'title' => $title,
                'body' => $body,
            ];

            foreach ($tokens as $fcmToken) {
                $notification = [
                    "message" => [
                        "token" => $fcmToken,
                        "notification" => $fcmMsg,
                        "data" => $data,
                        "android" => [
                            "priority" => "high",
                            "notification" => [
                                "sound" => "default"
                            ]
                        ],
                        "apns" => [
                            "payload" => [
                                "aps" => [
                                    "sound" => "default"
                                ]
                            ]
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
                $error = curl_error($ch);
                curl_close($ch);

                if ($error) {
                    Log::error('Curl Error', ['error' => $error]);
                } else {
                    $result = json_decode($response, true);
                    if (isset($result['error'])) {
                        Log::error('FCM Error', ['response' => $result]);
                    } else {
                        Log::info('Notification sent successfully', ['token' => $fcmToken]);
                    }
                }
            }

        } catch (\Exception $ex) {
            Log::error('Notification error', ['error' => $ex->getMessage()]);
        }
    }

    function generateAccessToken($credentials)
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


    public function generateInvoicePDF($order_details)
    {
        try {
            $data = [
                'created_at' => $order_details->created_at,
                'booking_date' => $order_details->booking_date,
                'booking_time' => $order_details->booking_time,
                'order_number' => $order_details->number,
                'payment_status' => $order_details->payment_status,
                'payment_method' => $order_details->payment_method,
                'total_price' => $order_details->total_price,
                'totalBeforeDiscount' => $order_details->totalBeforeDiscount,
                'car_name' => $order_details->car->getCurrentNameAttribute() ?? 'N/A',
                'car_model' => $order_details->car_model,
                'car_number' => $order_details->car_number,
                'user_name' => $order_details->user->first_name . ' ' . $order_details->user->family_name,
                'user_phone' => $order_details->user->phone_number,
                'discount_applied' => $order_details->discount_applied,
                'service_name' => optional($order_details->service)->getCurrentNameAttribute()
                    ?? optional(optional($order_details->userPackage)->package)->getCurrentNameAttribute()
                        ?? 'N/A',
                'service_duration' => optional($order_details->service)->duration ?? optional(optional($order_details->userPackage)->package)->duration ?? 'N/A',
                'service_price' => optional($order_details->service)->price
                    ?? optional(optional($order_details->userPackage)->package)->price
                        ?? 0,
                'service_choices' => $order_details->choices ?? collect([]),
            ];

            // Render Blade template as HTML
            $html = view('invoice.invoice', ['data' => $data])->render();
            
            // Check if HTML is empty
            if (empty(trim($html))) {
                Log::error('Invoice HTML is empty', ['order_id' => $order_details->id]);
                throw new \Exception('Failed to generate invoice HTML');
            }

            // Create MPDF instance
            $mpdf = new Mpdf(['tempDir' => storage_path('temp')]); // Specify a temp directory if needed

            // Write the HTML content
            $mpdf->WriteHTML($html);

            // Generate PDF content
            $pdfContent = $mpdf->Output('', 'S'); // S = return as string

            $fileName = 'invoice_' . time() . '_' . Str::random(5) . '.pdf';
            $filePath = 'invoices/' . $fileName;

            // Save PDF to storage
            Storage::disk('public')->put($filePath, $pdfContent);

            return $filePath;
        } catch (\Exception $e) {
            Log::error('Invoice generation failed', [
                'order_id' => $order_details->id ?? 'unknown',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

}