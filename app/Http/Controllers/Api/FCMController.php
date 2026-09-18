<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Google\Auth\Credentials\ServiceAccountCredentials;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FCMController extends Controller
{
    public function getAccessToken(&$projectId = null): string
    {
        $credentialsFilePath = storage_path('app/json/firebase_credentials.json');
        if (!file_exists($credentialsFilePath)) {
            $credentialsFilePath = storage_path('app/json/firebase.json');
        }

        $projectId = 'bala-matrimony-bureau';

        if (file_exists($credentialsFilePath)) {
            $jsonContent = json_decode(file_get_contents($credentialsFilePath), true);
            if (!empty($jsonContent['project_id'])) {
                $projectId = $jsonContent['project_id'];
            }
        }

        if (!file_exists($credentialsFilePath)) {
            return '';
        }

        try {
            $credentials = new ServiceAccountCredentials(
                'https://www.googleapis.com/auth/firebase.messaging',
                $credentialsFilePath
            );
            $token = $credentials->fetchAuthToken();
            return $token['access_token'] ?? '';
        } catch (\Exception $e) {
            Log::error('FCM OAuth Token Error: ' . $e->getMessage());
            return '';
        }
    }

    public function sendFcmNotificationHelper($fcm, $title, $body, array $customData = []): void
    {
        if (empty($fcm)) {
            return;
        }

        $projectId = 'bala-matrimony-bureau';
        $accessToken = $this->getAccessToken($projectId);
        if (empty($accessToken)) {
            return;
        }

        $headers = [
            "Authorization: Bearer $accessToken",
            'Content-Type: application/json'
        ];

        $payload = [
            "message" => [
                "token" => $fcm,
                "notification" => [
                    "title" => $title,
                    "body" => $body,
                ],
            ]
        ];

        if (!empty($customData)) {
            $payload['message']['data'] = array_map('strval', $customData);
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_exec($ch);
        curl_close($ch);
    }

    public function sendFcmNotificationToAll($title, $body, array $customData = []): int
    {
        $tokens = DB::table('users')
            ->whereNotNull('device_token')
            ->where('device_token', '!=', '')
            ->pluck('device_token')
            ->unique()
            ->values()
            ->all();

        if (empty($tokens)) {
            return 0;
        }

        $projectId = 'bala-matrimony-bureau';
        $accessToken = $this->getAccessToken($projectId);
        if (empty($accessToken)) {
            return 0;
        }

        $headers = [
            "Authorization: Bearer $accessToken",
            'Content-Type: application/json'
        ];

        $successCount = 0;

        foreach ($tokens as $token) {
            try {
                $payload = [
                    "message" => [
                        "token" => $token,
                        "notification" => [
                            "title" => $title,
                            "body" => $body,
                        ],
                    ]
                ];

                if (!empty($customData)) {
                    $payload['message']['data'] = array_map('strval', $customData);
                }

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send");
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
                $response = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);

                if ($httpCode >= 200 && $httpCode < 300) {
                    $successCount++;
                }
            } catch (\Exception $e) {
                Log::warning('Failed sending FCM push to token: ' . $token . ' - ' . $e->getMessage());
            }
        }

        return $successCount;
    }
}
