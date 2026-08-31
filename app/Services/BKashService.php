<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Exception;

class BKashService
{
    private $baseUrl;
    private $appKey;
    private $appSecret;
    private $username;
    private $password;
    private $callbackUrl;

    public function __construct()
    {
        // bKash Sandbox Credentials
        $this->baseUrl = config('services.bkash.sandbox_url', 'https://sandbox.bkash.com');
        $this->appKey = config('services.bkash.app_key');
        $this->appSecret = config('services.bkash.app_secret');
        $this->username = config('services.bkash.username');
        $this->password = config('services.bkash.password');
        $this->callbackUrl = config('services.bkash.callback_url');
    }

    /**
     * Get authorization token from bKash
     */
    public function getToken()
    {
        try {
            $response = Http::withBasicAuth($this->username, $this->password)
                ->post($this->baseUrl . '/api/oauth/token', [
                    'app_key' => $this->appKey,
                    'app_secret' => $this->appSecret,
                ]);

            if ($response->successful()) {
                return $response->json()['id_token'];
            }

            throw new Exception('Failed to get bKash token: ' . $response->body());
        } catch (Exception $e) {
            throw new Exception('BKash Token Error: ' . $e->getMessage());
        }
    }

    /**
     * Create payment request
     */
    public function createPaymentRequest($invoice)
    {
        try {
            $token = $this->getToken();

            $response = Http::withToken($token)
                ->post($this->baseUrl . '/api/checkout/initialize', [
                    'amount' => $invoice->total_amount,
                    'currency' => 'BDT',
                    'intent' => 'sale',
                    'merchantInvoiceNumber' => $invoice->invoice_number,
                    'callbackURL' => $this->callbackUrl,
                ]);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'success' => true,
                    'paymentID' => $data['paymentID'],
                    'bkashURL' => $data['bkashURL'],
                ];
            }

            return [
                'success' => false,
                'message' => 'Payment request failed',
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Execute payment
     */
    public function executePayment($paymentID)
    {
        try {
            $token = $this->getToken();

            $response = Http::withToken($token)
                ->post($this->baseUrl . '/api/checkout/execute', [
                    'paymentID' => $paymentID,
                ]);

            if ($response->successful()) {
                $data = $response->json();
                
                if ($data['statusCode'] === '0000') {
                    return [
                        'success' => true,
                        'transactionID' => $data['trxID'],
                        'statusCode' => $data['statusCode'],
                        'statusMessage' => $data['statusMessage'],
                    ];
                }
            }

            return [
                'success' => false,
                'message' => 'Payment execution failed',
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Query payment status
     */
    public function queryPayment($paymentID)
    {
        try {
            $token = $this->getToken();

            $response = Http::withToken($token)
                ->post($this->baseUrl . '/api/checkout/query', [
                    'paymentID' => $paymentID,
                ]);

            return $response->json();
        } catch (Exception $e) {
            throw new Exception('Query Payment Error: ' . $e->getMessage());
        }
    }

    /**
     * Verify webhook signature
     */
    public function verifySignature($signature, $data)
    {
        $hash = hash_hmac('sha256', $data, $this->appSecret);
        return hash_equals($hash, $signature);
    }
}
