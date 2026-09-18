<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

trait SMSTrait
{
    private string $smsApiKey = "1a9c42d41d6ee7c0537bfa1333288ffa";
    private int $smsRoute = 2;
    private string $smsSender = "KAMVAR";

    private array $templates = [
        'register'      => '1007229038537836272',
        'forgot'        => '1007328742905461440',
        'pwdChanged'    => '1007235029506734210',
        'welcome'       => '1007459768522549985',
        'payment'       => '1007415687825432512',
        'planSuccess'   => '1007437173932889904',
        'otpLogin'      => '1007320200801321581',
    ];

    /**
     * Send an SMS message dynamically.
     *
     * @param string $mobile Recipient's mobile number
     * @param string $type Template type (e.g., 'otpLogin', 'register')
     * @param array $data Dynamic placeholders ['OTP' => '1234']
     * @return bool Success status
     */
    public function sendSMS(string $mobile, string $type, array $data = []): bool
    {
        try {
            if (!isset($this->templates[$type])) {
                Log::error("Invalid SMS type: {$type}");
                return false;
            }

            $message = $this->prepareMessage($type, $data);

            $response = Http::get('https://sms.co3.live/api/smsapi', [
                'key'        => $this->smsApiKey,
                'route'      => $this->smsRoute,
                'sender'     => $this->smsSender,
                'number'     => $mobile,
                'templateid' => $this->templates[$type],
                'sms'        => $message,
            ]);

            if ($response->successful()) {
                return true;
            }

            Log::error("SMS failed: " . $response->body());
            return false;

        } catch (\Exception $e) {
            Log::error("SMS sending failed for {$type}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Replace placeholders dynamically.
     *
     * @param string $type Template type
     * @param array $data Placeholder values (e.g., ['OTP' => '1234'])
     * @return string Processed message
     */
    private function prepareMessage(string $type, array $data): string
    {
        $messages = [
            'register'   => "KNM Profile Registration Completed successfully. KAMMAVAR NAIDU MATRIMONY.",
            'otpLogin'   => "Your OTP is {OTP} to login Kammavar Naidu Matrimony.",
            'pwdChanged' => "{NAME} Your Password is changed. Kammavar Naidu Matrimony",
            'forgot' => "KNM is send Forgot mail to Your mail {MAIL} - KAMVAR",
        ];

        $message = $messages[$type] ?? '';

        foreach ($data as $key => $value) {
            $message = str_replace("{" . strtoupper($key) . "}", $value, $message);
        }

        return $message;
    }

}
