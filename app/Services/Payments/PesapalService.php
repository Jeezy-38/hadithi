<?php

namespace App\Services\Payments;

use App\Models\Payment;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class PesapalService implements PaymentGatewayInterface
{
    protected string $mode;

    protected ?string $consumerKey;

    protected ?string $consumerSecret;

    protected ?string $ipnId;

    public function __construct()
    {
        $this->mode = (string) config('services.pesapal.mode', 'sandbox');
        $this->consumerKey = config('services.pesapal.consumer_key');
        $this->consumerSecret = config('services.pesapal.consumer_secret');
        $this->ipnId = config('services.pesapal.ipn_id');
    }

    public function getBaseUrl(): string
    {
        return $this->mode === 'live'
            ? 'https://pay.pesapal.com/v3'
            : 'https://cybqa.pesapal.com/pesapalv3';
    }

    /**
     * Pata token ya JWT kutoka Pesapal (ikiwa na caching).
     */
    public function getAccessToken(): ?string
    {
        if (empty($this->consumerKey) || empty($this->consumerSecret)) {
            Log::warning('Pesapal: Consumer Key au Consumer Secret haijawekwa kwenye usanidi.');

            return null;
        }

        $cacheKey = 'pesapal_access_token_' . md5($this->consumerKey . $this->mode);

        return Cache::remember($cacheKey, now()->addMinutes(4), function () {
            try {
                $response = Http::withHeaders([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ])->post($this->getBaseUrl() . '/api/Auth/RequestToken', [
                    'consumer_key' => $this->consumerKey,
                    'consumer_secret' => $this->consumerSecret,
                ]);

                if ($response->successful()) {
                    $data = $response->json();

                    return $data['token'] ?? null;
                }

                Log::error('Pesapal Auth Failed: ' . $response->body());

                return null;
            } catch (Throwable $e) {
                Log::error('Pesapal Auth Exception: ' . $e->getMessage());

                return null;
            }
        });
    }

    /**
     * Sajili anwani ya IPN ya kupokea taarifa za malipo chinichini.
     */
    public function registerIpn(string $ipnUrl): ?string
    {
        $token = $this->getAccessToken();
        if (! $token) {
            return null;
        }

        try {
            $response = Http::withToken($token)->post($this->getBaseUrl() . '/api/URLSetup/RegisterIPN', [
                'url' => $ipnUrl,
                'ipn_notification_type' => 'GET',
            ]);

            if ($response->successful()) {
                $data = $response->json();

                return $data['ipn_id'] ?? null;
            }

            Log::error('Pesapal IPN Registration Failed: ' . $response->body());

            return null;
        } catch (Throwable $e) {
            Log::error('Pesapal IPN Registration Exception: ' . $e->getMessage());

            return null;
        }
    }

    /**
     * Tuma agizo la malipo kwenda Pesapal (SubmitOrderRequest).
     */
    public function initiateOrder(Payment $payment, array $options = []): array
    {
        $token = $this->getAccessToken();
        if (! $token) {
            return [
                'status' => 'error',
                'message' => 'Hitilafu ya mawasiliano na lango la Pesapal: Funguo za API hazijathibitishwa.',
            ];
        }

        $ipnId = $this->ipnId;
        if (empty($ipnId)) {
            $ipnId = $this->registerIpn(route('payments.ipn'));
        }

        $names = explode(' ', trim((string) ($payment->donor_name ?: 'Mchangiaji Mkarimu')), 2);
        $firstName = $names[0] ?: 'Mchangiaji';
        $lastName = $names[1] ?? 'Mkarimu';

        $description = $payment->purpose === 'supporter_membership'
            ? 'Uanachama wa Supporter · Bayt Al-Hadith'
            : 'Sadaka Jariya · Bayt Al-Hadith';

        $payload = [
            'id' => $payment->reference,
            'currency' => $payment->currency,
            'amount' => (float) $payment->amount,
            'description' => $description,
            'callback_url' => route('payments.callback'),
            'notification_id' => $ipnId,
            'billing_address' => [
                'email_address' => $payment->donor_email ?: 'support@baytalhadith.app',
                'phone_number' => $payment->donor_phone ?: '',
                'country_code' => 'TZ',
                'first_name' => $firstName,
                'last_name' => $lastName,
                'line_1' => 'Tanzania',
                'city' => 'Dar es Salaam',
            ],
        ];

        try {
            $response = Http::withToken($token)->post(
                $this->getBaseUrl() . '/api/Transactions/SubmitOrderRequest',
                $payload
            );

            if ($response->successful()) {
                $data = $response->json();
                $trackingId = $data['order_tracking_id'] ?? null;
                $redirectUrl = $data['redirect_url'] ?? null;

                if ($trackingId && $redirectUrl) {
                    $payment->update([
                        'gateway_reference' => $trackingId,
                        'metadata' => array_merge($payment->metadata ?? [], [
                            'pesapal_order' => $data,
                        ]),
                    ]);

                    return [
                        'status' => 'success',
                        'tracking_id' => $trackingId,
                        'redirect_url' => $redirectUrl,
                    ];
                }
            }

            Log::error('Pesapal Submit Order Failed: ' . $response->body());

            return [
                'status' => 'error',
                'message' => 'Lango la Pesapal halijaweza kuandaa ankara ya malipo. Tafadhali jaribu tena.',
            ];
        } catch (Throwable $e) {
            Log::error('Pesapal Submit Order Exception: ' . $e->getMessage());

            return [
                'status' => 'error',
                'message' => 'Hitilafu ya mtandao wakati wa kuanzisha malipo: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Ulizia hadhi ya muamala kutoka Pesapal (GetTransactionStatus).
     */
    public function getTransactionStatus(string $orderTrackingId): array
    {
        $token = $this->getAccessToken();
        if (! $token) {
            return [
                'status' => 'error',
                'message' => 'Funguo za Pesapal hazipatikani.',
            ];
        }

        try {
            $response = Http::withToken($token)->get(
                $this->getBaseUrl() . '/api/Transactions/GetTransactionStatus',
                ['orderTrackingId' => $orderTrackingId]
            );

            if ($response->successful()) {
                $data = $response->json();
                $statusDesc = strtolower((string) ($data['payment_status_description'] ?? ''));
                $statusCode = (int) ($data['status_code'] ?? 0);

                // Pesapal statusCode: 1 = Completed, 2 = Failed, 3 = Reversed, 0 = Invalid
                $normalizedStatus = 'pending';
                if ($statusCode === 1 || $statusDesc === 'completed') {
                    $normalizedStatus = 'completed';
                } elseif ($statusCode === 2 || $statusDesc === 'failed') {
                    $normalizedStatus = 'failed';
                } elseif ($statusCode === 3 || $statusDesc === 'reversed') {
                    $normalizedStatus = 'refunded';
                }

                return [
                    'status' => $normalizedStatus,
                    'amount' => isset($data['amount']) ? (float) $data['amount'] : null,
                    'currency' => $data['currency'] ?? null,
                    'payment_method' => $data['payment_method'] ?? null,
                    'gateway_reference' => $orderTrackingId,
                    'raw_status' => $data['payment_status_description'] ?? null,
                    'confirmation_code' => $data['confirmation_code'] ?? null,
                ];
            }

            Log::error('Pesapal Get Status Failed: ' . $response->body());

            return [
                'status' => 'error',
                'message' => 'Haikuweza kuthibitisha hadhi kutoka Pesapal.',
            ];
        } catch (Throwable $e) {
            Log::error('Pesapal Get Status Exception: ' . $e->getMessage());

            return [
                'status' => 'error',
                'message' => 'Hitilafu ya mtandao wakati wa kuulizia hadhi: ' . $e->getMessage(),
            ];
        }
    }
}
