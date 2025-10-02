<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WablasService
{
    protected $baseUrl;
    protected $token;

    public function __construct()
    {
        $this->baseUrl = config('services.wablas.base_url', 'https://sby.wablas.com');
        $this->token = config('services.wablas.token');
    }

    /**
     * Kirim pesan WhatsApp
     */
    public function sendMessage($phone, $message)
    {
        if (empty($this->token)) {
            return ['success' => false, 'error' => 'Wablas token tidak ditemukan'];
        }

        try {
            Log::info('Sending WhatsApp via Surabaya server', [
                'phone' => $phone,
                'url' => $this->baseUrl . '/api/send-message',
                'token' => substr($this->token, 0, 10) . '...'
            ]);

          $response = Http::withHeaders([
                'Authorization' => $this->token,
            ])->timeout(30)->post($this->baseUrl . '/api/send-message', [
                'phone' => $this->formatPhoneNumber($phone),
                'message' => $message,
            ]);


            Log::info('Wablas Response', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            if ($response->successful()) {
                return ['success' => true, 'data' => $response->json()];
            }

            return ['success' => false, 'error' => 'HTTP ' . $response->status() . ': ' . $response->body()];

        } catch (\Exception $e) {
            Log::error('Wablas error', [
                'error' => $e->getMessage(),
                'phone' => $phone
            ]);

            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Test koneksi
     */
    public function testConnection()
    {
        try {
            $response = Http::withHeaders([
                    'Authorization' => $this->token,
                ])->timeout(30)->get($this->baseUrl . '/api/device-status');

            return [
                'success' => $response->successful(),
                'status' => $response->status(),
                'data' => $response->successful() ? $response->json() : $response->body()
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Format nomor telepon
     */
    protected function formatPhoneNumber($phone)
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        if (substr($phone, 0, 1) === '0') {
            $phone = '62' . substr($phone, 1);
        } elseif (substr($phone, 0, 2) !== '62') {
            $phone = '62' . $phone;
        }
        
        return $phone;
    }
}