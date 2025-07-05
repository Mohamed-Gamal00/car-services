<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendPushNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $token;
    protected $title;
    protected $body;
    protected $accessToken;
    protected $url;

    /**
     * Create a new job instance.
     *
     * @param string $token
     * @param string $title
     * @param string $body
     * @param string $accessToken
     */
    public function __construct($token, $title, $body, $accessToken)
    {
        $this->token = $token;
        $this->title = $title;
        $this->body = $body;
        $this->accessToken = $accessToken;
        $this->url = 'https://fcm.googleapis.com/v1/projects/quickclean-27487/messages:send';
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data = [
            "message" => [
                "token" => trim($this->token),
                "notification" => [
                    "title" => $this->title,
                    "body" => $this->body,
                ],
            ]
        ];

        $headers = [
            "Authorization: Bearer {$this->accessToken}",
            'Content-Type: application/json',
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $result = curl_exec($ch);
        if ($result === FALSE) {
            Log::error('Curl failed: ' . curl_error($ch));
        } else {
            $response = json_decode($result, true);
            if (isset($response['error'])) {
                $errorCode = $response['error']['details'][0]['errorCode'] ?? null;
                if ($errorCode === 'UNREGISTERED') {
                    Log::info("Removing unregistered token: {$this->token}");
                } else {
                    Log::error('Notification error: ' . json_encode($response));
                }
            } else {
                Log::info("Notification sent to token: {$this->token}");
            }
        }

        curl_close($ch);
    }
}
