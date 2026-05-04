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
            Log::info('Starting invoice generation', [
                'order_id' => $order_details->id ?? 'unknown',
                'order_number' => $order_details->number ?? 'unknown'
            ]);

            // Ensure relationships are loaded with detailed logging
            if (!$order_details->relationLoaded('user')) {
                Log::info('Loading user relationship');
                $order_details->load('user');
            }
            if (!$order_details->relationLoaded('car')) {
                Log::info('Loading car relationship');
                $order_details->load('car');
            }
            if (!$order_details->relationLoaded('choices')) {
                Log::info('Loading choices relationship');
                $order_details->load('choices');
            }
            if (!$order_details->relationLoaded('service')) {
                Log::info('Loading service relationship');
                $order_details->load('service');
            }
            if (!$order_details->relationLoaded('userPackage')) {
                Log::info('Loading userPackage relationship');
                $order_details->load('userPackage.package');
            }

            // Verify critical relationships exist
            if (!$order_details->user) {
                Log::error('User relationship is null', ['order_id' => $order_details->id]);
            }
            if (!$order_details->service && !$order_details->userPackage) {
                Log::error('Both service and userPackage relationships are null', ['order_id' => $order_details->id]);
            }

            // Build data array with comprehensive null checks and defaults
            $userName = 'N/A';
            if ($order_details->user) {
                $firstName = $order_details->user->first_name ?? '';
                $familyName = $order_details->user->family_name ?? '';
                $userName = trim($firstName . ' ' . $familyName) ?: 'N/A';
            }

            $carName = 'N/A';
            if ($order_details->car) {
                try {
                    $carName = $order_details->car->getCurrentNameAttribute() ?? 'N/A';
                } catch (\Exception $e) {
                    Log::warning('Failed to get car name', ['error' => $e->getMessage()]);
                    $carName = 'N/A';
                }
            }

            $serviceName = 'N/A';
            $serviceDuration = 'N/A';
            $servicePrice = 0;

            if ($order_details->service) {
                try {
                    $serviceName = $order_details->service->getCurrentNameAttribute() ?? 'N/A';
                    $serviceDuration = $order_details->service->duration ?? 'N/A';
                    $servicePrice = $order_details->service->price ?? 0;
                } catch (\Exception $e) {
                    Log::warning('Failed to get service details', ['error' => $e->getMessage()]);
                }
            } elseif ($order_details->userPackage && $order_details->userPackage->package) {
                try {
                    $serviceName = $order_details->userPackage->package->getCurrentNameAttribute() ?? 'N/A';
                    $serviceDuration = $order_details->userPackage->package->duration ?? 'N/A';
                    $servicePrice = $order_details->userPackage->package->price ?? 0;
                } catch (\Exception $e) {
                    Log::warning('Failed to get package details', ['error' => $e->getMessage()]);
                }
            }

            $data = [
                'created_at' => $order_details->created_at ?? now(),
                'booking_date' => $order_details->booking_date ?? 'N/A',
                'booking_time' => $order_details->booking_time ?? 'N/A',
                'order_number' => $order_details->number ?? 'N/A',
                'payment_status' => $order_details->payment_status ?? 'pending',
                'payment_method' => $order_details->payment_method ?? 'N/A',
                'total_price' => $order_details->total_price ?? 0,
                'totalBeforeDiscount' => $order_details->totalBeforeDiscount ?? 0,
                'car_name' => $carName,
                'car_model' => $order_details->car_model ?? 'N/A',
                'car_number' => $order_details->car_number ?? 'N/A',
                'user_name' => $userName,
                'user_phone' => $order_details->user->phone_number ?? 'N/A',
                'discount_applied' => $order_details->discount_applied ?? null,
                'service_name' => $serviceName,
                'service_duration' => $serviceDuration,
                'service_price' => $servicePrice,
                'service_choices' => $order_details->choices ?? collect([]),
            ];

            Log::info('Invoice data prepared', [
                'order_id' => $order_details->id,
                'has_service' => !is_null($order_details->service),
                'has_package' => !is_null($order_details->userPackage),
                'choices_count' => $data['service_choices']->count()
            ]);

            // Render Blade template as HTML
            try {
                $html = view('invoice.invoice', ['data' => $data])->render();
            } catch (\Exception $e) {
                Log::error('Failed to render invoice view', [
                    'order_id' => $order_details->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                throw new \Exception('Failed to render invoice template: ' . $e->getMessage());
            }

            // Check if HTML is empty
            if (empty(trim($html))) {
                Log::error('Invoice HTML is empty after rendering', [
                    'order_id' => $order_details->id,
                    'data' => $data
                ]);
                throw new \Exception('Generated invoice HTML is empty');
            }

            Log::info('Invoice HTML generated', [
                'order_id' => $order_details->id,
                'html_length' => strlen($html)
            ]);

            // Create MPDF instance
            try {
                Log::info('Creating MPDF instance', ['order_id' => $order_details->id]);
                
                $mpdf = new Mpdf([
                    'tempDir' => storage_path('temp'),
                    'mode' => 'utf-8',
                    'format' => 'A4',
                    'margin_left' => 10,
                    'margin_right' => 10,
                    'margin_top' => 10,
                    'margin_bottom' => 10,
                ]);

                Log::info('MPDF instance created', ['order_id' => $order_details->id]);

                // Write the HTML content
                Log::info('Writing HTML to MPDF', ['order_id' => $order_details->id]);
                $mpdf->WriteHTML($html);
                
                Log::info('HTML written to MPDF', ['order_id' => $order_details->id]);

                // Generate PDF content
                Log::info('Generating PDF output', ['order_id' => $order_details->id]);
                $pdfContent = $mpdf->Output('', 'S'); // S = return as string
                
                Log::info('PDF content generated', [
                    'order_id' => $order_details->id,
                    'pdf_size' => strlen($pdfContent)
                ]);
            } catch (\Mpdf\MpdfException $e) {
                Log::error('MPDF Exception', [
                    'order_id' => $order_details->id,
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]);
                throw $e;
            }

            $fileName = 'invoice_' . time() . '_' . Str::random(5) . '.pdf';
            $filePath = 'invoices/' . $fileName;

            // Save PDF to storage
            Storage::disk('public')->put($filePath, $pdfContent);

            Log::info('Invoice PDF generated successfully', [
                'order_id' => $order_details->id,
                'file_path' => $filePath,
                'file_size' => strlen($pdfContent)
            ]);

            return $filePath;
        } catch (\Exception $e) {
            Log::error('Invoice generation failed in Helper', [
                'order_id' => $order_details->id ?? 'unknown',
                'order_number' => $order_details->number ?? 'unknown',
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            // Don't throw - return null to allow payment to continue
            return null;
        }
    }
}
