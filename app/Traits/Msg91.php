<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

trait Msg91
{
    private string $apiKey = "454327AE77XcB5NohV684d1976P1";
    private string $apiUrl = "https://control.msg91.com/api/v5/otp";
    private string $smsUrl = "https://control.msg91.com/api/v5/flow";

    /**
     * Template IDs for different OTP scenarios.
     */
    private array $templateIds = [
        'login_otp'         => '684fb30dd6fc05077c2b6e53',
        'signup_otp'         => '684fb3bfd6fc0535aa0bd593',
        'password_reset_otp'         => '684fb446d6fc050221534043',
        'account_activation'  => '684fb562d6fc053403065cf2',
        'account_activation_pending'  => '684fb5a4d6fc057d60119713',
    ];

    public function sendMsg91(string $mobileNumber, ?int $otp, string $type, int $expiryMinutes = 3): array
    {
        if (! isset($this->templateIds[$type])) {
            return ['success' => false, 'message' => 'Invalid OTP template type provided.'];
        }

        $payload = [
            'template_id'      => $this->templateIds[$type],
            'mobile'           => $mobileNumber,
            'authkey'          => $this->apiKey,
            'otp'              => $otp,
            'realTimeResponse' => 1,
        ];

        $payload['number'] = $expiryMinutes;

        try {
            $response = Http::withHeaders(['Content-Type' => 'application/json'])
                ->post($this->apiUrl, $payload);
            $body = $response->body();

            if ($response->successful() && str_contains($body, '"type":"success"')) {
                return ['success' => true, 'message' => 'OTP sent successfully.'];
            }

            Log::error("MSG91 OTP send failed. Response: {$body}");
            return ['success' => false, 'message' => 'Failed to send OTP. Response: ' . $body];
        } catch (\Exception $e) {
            Log::error('MSG91 error: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Error sending OTP: ' . $e->getMessage()];
        }
    }

    public function sendMsg91Flow(string $mobileNumber, string $templateType, array $variables = []): array
    {
        if (!isset($this->templateIds[$templateType])) {
            return ['success' => false, 'message' => 'Invalid template type provided.'];
        }
        $recipient = ['mobiles' => $mobileNumber];
        try {
            $response = Http::withHeaders([
                'authkey'       => $this->apiKey,
                'accept'        => 'application/json',
                'content-type'  => 'application/json',
            ])->post($this->smsUrl, [
                'template_id'      => $this->templateIds[$templateType],
                'short_url'        => 0,
                'realTimeResponse' => 1,
                'recipients'       => [$recipient],
            ]);
            $responseBody = $response->json();

            if ($response->successful() && ($responseBody['type'] ?? '') === 'success') {
                return ['success' => true, 'message' => 'SMS sent successfully.'];
            }
            Log::error('MSG91 Flow Failed: ' . json_encode($responseBody));
            return ['success' => false, 'message' => 'Failed to send SMS. ' . json_encode($responseBody)];
        } catch (\Exception $e) {
            Log::error('MSG91 Flow Error: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Error sending SMS: ' . $e->getMessage()];
        }
    }

}
