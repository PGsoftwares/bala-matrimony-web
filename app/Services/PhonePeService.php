<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class PhonePeService
{
    protected array $config;
    protected array $endpoints;

    public function __construct()
    {
        $this->config    = config('phonepe');
        $mode            = $this->config['mode'];
        $this->endpoints = $this->config[$mode];
    }

    public function url(string $type): string
    {
        return $this->endpoints['base_url'] . $this->endpoints[$type];
    }

    public function getAuthToken(): ?string
    {
        $response = Http::asForm()->post($this->url('authorization') . '/v1/oauth/token', [
            'client_id'     => $this->config['client_id'],
            'client_secret' => $this->config['client_secret'],
            'client_version'=> $this->config['client_version'],
            'grant_type'    => 'client_credentials',
        ]);

        return $response->successful() ? $response['access_token'] : null;
    }

    public function createOrder(string $accessToken, array $payload)
    {
        return Http::withHeaders([
            'Authorization' => 'O-Bearer ' . $accessToken,
            'Content-Type'  => 'application/json',
        ])->post($this->url('payment') . '/checkout/v2/pay', $payload);
    }

    public function checkOrderStatus(string $accessToken, string $orderId)
    {
        return Http::withHeaders([
            'Authorization' => 'O-Bearer ' . $accessToken,
            'Content-Type'  => 'application/json',
        ])->get($this->url('order_status') . "/checkout/v2/order/{$orderId}/status");
    }
}
