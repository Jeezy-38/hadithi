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

    protected ?string $lastAuthError = null;

    protected string $baseUrl = 'https://api.clickpesa.com/third-parties';

    public function __construct()
    {
        $this->clientId = trim((string) config('services.clickpesa.client_id'));
        $this->apiKey = trim((string) config('services.clickpesa.api_key'));
        $this->checksumKey = trim((string) config('services.clickpesa.checksum_key'));
        $this->mode = trim((string) config('services.clickpesa.mode', 'live'));
    }

    public function getLastAuthError(): ?string
    {
        return $this->lastAuthError;
    }

    /**
     * Pata token ya JWT kutoka ClickPesa (ikiwa na caching ya dakika 50).
     */
    public function getAccessToken(): ?string
    {
        if (empty($this->clientId) || empty($this->apiKey)) {
            $this->lastAuthError = 'Funguo za CLICKPESA_CLIENT_ID na CLICKPESA_API_KEY hazijawekwa kwenye Environment Variables.';
            Log::warning('ClickPesa: Client ID au API Key haijawekwa kwenye usanidi.');

            return null;
        }

        $cacheKey = 'clickpesa_access_token_' . md5($this->clientId);

        $cachedToken = Cache::get($cacheKey);
        if ($cachedToken) {
            return $cachedToken;
        }

        try {
            $response = Http::withHeaders([
                'client-id' => $this->clientId,
                'api-key' => $this->apiKey,
                'Accept' => 'application/json',
            ])->post($this->baseUrl . '/generate-token');

            if ($response->successful()) {
                $token = $response->json('token') ?? $response->json('data.token');
                if ($token) {
                    $cleanToken = preg_replace('/^Bearer\s+/i', '', trim((string) $token));
                    Cache::put($cacheKey, $cleanToken, now()->addMinutes(50));

                    return $cleanToken;
                }
            }

            $rawMsg = $response->json('message') ?? $response->body();
            if (is_array($rawMsg)) {
                $rawMsg = implode(', ', $rawMsg);
            }
            $this->lastAuthError = (string) $rawMsg;
            Log::error('ClickPesa Auth Failed: ' . $response->body());

            return null;
        } catch (Throwable $e) {
            $this->lastAuthError = $e->getMessage();
            Log::error('ClickPesa Auth Exception: ' . $e->getMessage());

            return null;
        }
    }

    /**
     * Anzisha kiungo cha Hosted Checkout kutoka ClickPesa (Checkout Link API).
     */
    public function initiateOrder(Payment $payment, array $options = []): array
    {
        $token = $this->getAccessToken();
        if (! $token) {
            $authErr = $this->getLastAuthError();
            if ($authErr && str_contains($authErr, 'Too many token requests')) {
                $friendly = 'Seva ya ClickPesa imeweka zuio la muda ("Too many token requests"). Tafadhali subiri dakika 2-3 kisha ujaribu tena.';
            } elseif ($authErr && str_contains($authErr, 'Invalid client details')) {
                $friendly = 'ClickPesa imekataa Client ID au API Key ("Invalid client details"). Kagua faili lako la .env (au .env kwenye seva/hosting) uhakikishe CLICKPESA_CLIENT_ID na CLICKPESA_API_KEY zimeandikwa kwa usahihi bila kukosa herufi.';
            } elseif ($authErr) {
                $friendly = 'ClickPesa: ' . $authErr;
            } else {
                $friendly = 'Hitilafu ya mawasiliano na lango la ClickPesa: Funguo za API hazijathibitishwa na seva ya ClickPesa.';
            }

            return [
                'status' => 'error',
                'message' => $friendly,
            ];
        }

        $description = $payment->purpose === 'supporter_membership'
            ? 'Uanachama wa Supporter · Bayt Ul Hadith'
            : 'Sadaka Jariya · Bayt Ul Hadith';

        $amount = (string) (int) round((float) $payment->amount);
        $callbackUrl = route('payments.callback', ['reference' => $payment->reference]);

        // ClickPesa inakubali herufi na namba pekee kwenye orderReference (hakuna vistari).
        $orderReference = preg_replace('/[^A-Za-z0-9]/', '', (string) $payment->reference);

        // Namba ya simu: 0668416084 / +255668416084 -> 255668416084 (code ya nchi, bila +).
        $phone = preg_replace('/\D+/', '', (string) $payment->donor_phone);
        if ($phone !== '') {
            if (str_starts_with($phone, '0')) {
                $phone = '255' . substr($phone, 1);
            } elseif (strlen($phone) === 9) {
                $phone = '255' . $phone;
            }
        }

        $payload = [
            'totalPrice' => $amount,
            'orderCurrency' => $payment->currency ?: 'TZS',
            'currency' => $payment->currency ?: 'TZS',
            'orderReference' => $orderReference,
            'customerName' => $payment->donor_name ?: 'Mtoaji Sadaka',
            'customerEmail' => $payment->donor_email ?: 'sadaka@baytalhadith.com',
            'customerPhone' => $phone,
            'description' => $description,
            'callbackUrl' => $callbackUrl,
            'orderItems' => [
                [
                    'name' => $description,
                    'price' => $amount,
                    'quantity' => 1,
                ],
            ],
        ];

        // Ambatanisha Checksum ikiwa Checksum Key imesanidiwa
        if (! empty($this->checksumKey)) {
            $payload['checksum'] = $this->computeChecksum($payload, $this->checksumKey);
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->post($this->baseUrl . '/checkout-link/generate-checkout-url', $payload);

            if ($response->successful()) {
                $checkoutLink = $response->json('checkoutLink')
                    ?? $response->json('data.checkoutLink')
                    ?? $response->json('checkoutUrl')
                    ?? $response->json('data.checkoutUrl')
                    ?? $response->json('url');

                if ($checkoutLink) {
                    $payment->update([
                        'gateway' => 'clickpesa',
                        'gateway_reference' => $orderReference,
                        'metadata' => array_merge($payment->metadata ?? [], [
                            'clickpesa_response' => $response->json(),
                        ]),
                    ]);

                    return [
                        'status' => 'success',
                        'tracking_id' => $orderReference,
                        'redirect_url' => $checkoutLink,
                    ];
                }
            }

            $rawMsg = $response->json('message');
            if (is_array($rawMsg)) {
                $rawMsg = implode(', ', $rawMsg);
            }
            $rawMsg = (string) ($rawMsg ?: $response->body());

            if (str_contains($rawMsg, 'CHECKOUT_LINK')) {
                $friendlyMsg = 'Akaunti yako ya ClickPesa inahitaji kuwezeshwa huduma ya "Checkout Link" kwenye ClickPesa Portal (Developers -> Applications -> Permissions -> Checkout Link).';
            } else {
                $friendlyMsg = $rawMsg ?: 'Tafadhali jaribu tena.';
            }

            Log::error('ClickPesa Generate Checkout Link Failed: ' . $response->body());

            return [
                'status' => 'error',
                'message' => 'Lango la ClickPesa: ' . $friendlyMsg,
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

    /**
     * Tengeneza Checksum kwa mujibu wa kanuni rasmi ya ClickPesa (HMAC-SHA256 na Canonicalization).
     */
    public function computeChecksum(array $payload, string $secretKey): string
    {
        unset($payload['checksum'], $payload['checksumMethod']);
        $canonical = $this->canonicalize($payload);
        $json = json_encode($canonical, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        return hash_hmac('sha256', $json, $secretKey);
    }

    /**
     * Panga safu (array) kwa herufi ili kuhakikisha muundo wa JSON unalingana.
     */
    protected function canonicalize(mixed $data): mixed
    {
        if (is_array($data)) {
            if (array_keys($data) !== range(0, count($data) - 1)) {
                ksort($data);
            }
            foreach ($data as $key => $value) {
                $data[$key] = $this->canonicalize($value);
            }
        }

        return $data;
    }
}
