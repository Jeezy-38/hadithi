<?php

namespace Tests\Feature;

use App\Models\Payment;
use App\Models\User;
use App\Services\Payments\ClickPesaService;
use App\Services\Payments\PaymentManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ClickPesaTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Config::set('services.clickpesa.client_id', 'test_client_123');
        Config::set('services.clickpesa.api_key', 'test_api_key_456');
        Config::set('services.clickpesa.mode', 'live');
    }

    public function test_clickpesa_authenticates_and_retrieves_token(): void
    {
        Http::fake([
            'https://api.clickpesa.com/third-parties/generate-token' => Http::response([
                'token' => 'Bearer mock_jwt_token_sample',
            ], 200),
        ]);

        $service = new ClickPesaService();
        $token = $service->getAccessToken();

        $this->assertEquals('mock_jwt_token_sample', $token);
    }

    public function test_clickpesa_initiates_checkout_link_order(): void
    {
        Http::fake([
            'https://api.clickpesa.com/third-parties/generate-token' => Http::response([
                'token' => 'mock_token',
            ], 200),
            'https://api.clickpesa.com/third-parties/checkout-link/generate-checkout-url' => Http::response([
                'checkoutLink' => 'https://checkout.clickpesa.com/pay/abc-123',
            ], 200),
        ]);

        $payment = Payment::create([
            'reference' => 'SADAKA-CP-001',
            'amount' => 5000,
            'currency' => 'TZS',
            'purpose' => 'sadaka_jariya',
            'status' => 'pending',
        ]);

        $service = new ClickPesaService();
        $result = $service->initiateOrder($payment);

        $this->assertEquals('success', $result['status']);
        $this->assertEquals('https://checkout.clickpesa.com/pay/abc-123', $result['redirect_url']);
        $this->assertEquals('SADAKA-CP-001', $result['tracking_id']);

        $payment->refresh();
        $this->assertEquals('clickpesa', $payment->gateway);
    }

    public function test_clickpesa_queries_transaction_status(): void
    {
        Http::fake([
            'https://api.clickpesa.com/third-parties/generate-token' => Http::response([
                'token' => 'mock_token',
            ], 200),
            'https://api.clickpesa.com/third-parties/payments/*' => Http::response([
                'data' => [
                    'status' => 'SUCCESS',
                    'collectedAmount' => '5000',
                    'collectedCurrency' => 'TZS',
                    'channel' => 'M-PESA',
                    'paymentReference' => 'MPESA12345',
                ],
            ], 200),
        ]);

        $service = new ClickPesaService();
        $status = $service->getTransactionStatus('SADAKA-CP-001');

        $this->assertEquals('completed', $status['status']);
        $this->assertEquals(5000, $status['amount']);
        $this->assertEquals('M-PESA', $status['payment_method']);
        $this->assertEquals('MPESA12345', $status['confirmation_code']);
    }

    public function test_payment_manager_picks_clickpesa_gateway(): void
    {
        Config::set('services.payment_gateway', 'clickpesa');

        $manager = app(PaymentManager::class);
        $gateway = $manager->gateway();

        $this->assertInstanceOf(ClickPesaService::class, $gateway);
    }

    public function test_clickpesa_webhook_fulfills_payment(): void
    {
        $user = User::factory()->create();

        $payment = Payment::create([
            'user_id' => $user->id,
            'reference' => 'SADAKA-WEBHOOK-99',
            'amount' => 10000,
            'currency' => 'TZS',
            'purpose' => 'supporter_membership',
            'tier' => 'ansar',
            'billing_cycle' => 'monthly',
            'status' => 'pending',
        ]);

        $payload = [
            'event' => 'PAYMENT RECEIVED',
            'data' => [
                'id' => 'ORD123',
                'status' => 'SUCCESS',
                'orderReference' => 'SADAKA-WEBHOOK-99',
                'paymentReference' => 'TIGO998877',
                'collectedAmount' => '10000',
                'collectedCurrency' => 'TZS',
                'channel' => 'TIGO PESA',
            ],
        ];

        $response = $this->postJson(route('payments.webhook.clickpesa'), $payload);

        $response->assertOk();
        $response->assertJson(['status' => 'success']);

        $payment->refresh();
        $this->assertEquals('completed', $payment->status);
        $this->assertNotNull($payment->completed_at);

        $user->refresh();
        $this->assertTrue($user->is_supporter);
    }

    public function test_clickpesa_test_artisan_command_success(): void
    {
        Http::fake([
            'https://api.clickpesa.com/third-parties/generate-token' => Http::response([
                'token' => 'Bearer token_abc',
            ], 200),
        ]);

        $this->artisan('clickpesa:test')
            ->expectsOutputToContain('HONGERA! Mawasiliano na ClickPesa yamefaulu kwa 100%')
            ->assertSuccessful();
    }
}
