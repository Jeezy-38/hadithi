<?php

namespace App\Services\Payments;

use App\Models\Payment;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class ClickPesaService implements PaymentGatewayInterface
{
    protected ?string $clientId;

    protected ?string $apiKey;

    protected ?string $checksumKey;

    protected string $mode;

    protected string $baseUrl = 'https://api.clickpesa.com/third-parties';

    public function __construct()
    {
        $this->clientId = config('services.clickpesa.client_id');
        $this->apiKey = config('services.clickpesa.api_key');
        $this->checksumKey = config('services.clickpesa.checksum_key');
        $this->mode = (string) config('services.clickpesa.mode', 'live');
    }

    /**
     * Pata token ya JWT kutoka ClickPesa (ikiwa na caching ya dakika 50).
     */
    public function getAccessToken(): ?string
    {
        if (empty($this->clientId) || empty($this->apiKey)) {
            Log::warning('ClickPesa: Client ID au API Key haijawekwa kwenye usanidi.');

            return null;
        }

        $cacheKey = 'clickpesa_access_token_' . md5($this->clientId);

        return Cache::remember($cacheKey, now()->addMinutes(50), function () {
            try {
                $response = Http::withHeaders([
                    'client-id' => $this->clientId,
                    'api-key' => $this->apiKey,
                    'Accept' => 'application/json',
                ])->post($this->baseUrl . '/generate-token');

                if ($response->successful()) {
                    $token = $response->json('token') ?? $response->json('data.token');
                    if ($token) {
                        // Hakikisha hakuna marudio ya "Bearer "
                        return preg_replace('/^Bearer\s+/i', '', trim((string) $token));
                    }
                }

                Log::error('ClickPesa Auth Failed: ' . $response->body());

                return null;
            } catch (Throwable $e) {
                Log::error('ClickPesa Auth Exception: ' . $e->getMessage());

                return null;
            }
        });
    }

    /**
     * Anzisha kiungo cha Hosted Checkout kutoka ClickPesa (Checkout Link API).
     */
    public function initiateOrder(Payment $payment, array $options = []): array
    {
        $token = $this->getAccessToken();
        if (! $token) {
            return [
                'status' => 'error',
                'message' => 'Hitilafu ya mawasiliano na lango la ClickPesa: Funguo za API (Client ID / API Key) hazijathibitishwa.',
            ];
        }

        $description = $payment->purpose === 'supporter_membership'
            ? 'Uanachama wa Supporter · Hadith App'
            : 'Sadaka Jariya · Hadith App';

        $amount = (string) (int) round((float) $payment->amount);
        $callbackUrl = route('payments.callback', ['reference' => $payment->reference]);

        $payload = [
            'totalPrice' => $amount,
            'currency' => $payment->currency ?: 'TZS',
            'orderReference' => $payment->reference,
            'callbackUrl' => $callbackUrl,
            'orderItems' => [
                [
                    'name' => $description,
                    'price' => $amount,
                    'quantity' => 1,
                ],
            ],
        ];

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->post($this->baseUrl . '/checkout-link/generate-checkout-url', $payload);

            if ($response->successful()) {
                $checkoutLink = $response->json('checkoutLink')
                    ?? $response->json('data.checkoutLink')
                    ?? $response->json('url');

                if ($checkoutLink) {
                    $payment->update([
                        'gateway' => 'clickpesa',
                        'gateway_reference' => $payment->reference,
                        'metadata' => array_merge($payment->metadata ?? [], [
                            'clickpesa_response' => $response->json(),
                        ]),
                    ]);

                    return [
                        'status' => 'success',
                        'tracking_id' => $payment->reference,
                        'redirect_url' => $checkoutLink,
                    ];
                }
            }

            Log::error('ClickPesa Generate Checkout Link Failed: ' . $response->body());

            return [
                'status' => 'error',
                'message' => 'Lango la ClickPesa halijaweza kuandaa ankara ya malipo: ' . ($response->json('message') ?? 'Tafadhali jaribu tena.'),
            ];
        } catch (Throwable $e) {
            Log::error('ClickPesa Generate Checkout Exception: ' . $e->getMessage());

            return [
                'status' => 'error',
                'message' => 'Hitilafu ya mtandao wakati wa kuwasiliana na ClickPesa: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Ulizia hadhi ya muamala kutoka ClickPesa (GET /payments/{orderReference}).
     */
    public function getTransactionStatus(string $orderTrackingId): array
    {
        $token = $this->getAccessToken();
        if (! $token) {
            return [
                'status' => 'error',
                'message' => 'Funguo za ClickPesa hazipatikani.',
            ];
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json',
            ])->get($this->baseUrl . '/payments/' . urlencode($orderTrackingId));

            if ($response->successful()) {
                $data = $response->json('data') ?? $response->json();
                $rawStatus = strtoupper((string) ($data['status'] ?? ''));

                $normalizedStatus = 'pending';
                if (in_array($rawStatus, ['SUCCESS', 'SETTLED', 'COMPLETED'], true)) {
                    $normalizedStatus = 'completed';
                } elseif (in_array($rawStatus, ['FAILED', 'CANCELLED', 'REVERSED'], true)) {
                    $normalizedStatus = 'failed';
                }

                return [
                    'status' => $normalizedStatus,
                    'amount' => isset($data['collectedAmount']) ? (float) $data['collectedAmount'] : (float) ($data['amount'] ?? 0),
                    'currency' => $data['collectedCurrency'] ?? $data['currency'] ?? 'TZS',
                    'payment_method' => $data['channel'] ?? $data['paymentMethod'] ?? 'mobile_money',
                    'gateway_reference' => $data['paymentReference'] ?? $data['id'] ?? $orderTrackingId,
                    'raw_status' => $rawStatus,
                    'confirmation_code' => $data['paymentReference'] ?? null,
                ];
            }

            Log::error('ClickPesa Query Status Failed: ' . $response->body());

            return [
                'status' => 'error',
                'message' => 'Haikuweza kuthibitisha hadhi kutoka ClickPesa.',
            ];
        } catch (Throwable $e) {
            Log::error('ClickPesa Query Status Exception: ' . $e->getMessage());

            return [
                'status' => 'error',
                'message' => 'Hitilafu ya mtandao wakati wa kuulizia hadhi: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Sajili anwani ya Webhook au IPN.
     */
    public function registerIpn(string $ipnUrl): ?string
    {
        return $ipnUrl;
    }
}
