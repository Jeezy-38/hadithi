<?php

namespace Tests\Feature;

use App\Livewire\SupportIndex;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class SupportAndPaymentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'services.payment_gateway' => 'simulation',
            'services.clickpesa.client_id' => null,
            'services.clickpesa.api_key' => null,
            'services.pesapal.mode' => 'simulation',
        ]);
    }

    public function test_support_page_renders_successfully(): void
    {
        $response = $this->get(route('support'));

        $response->assertStatus(200);
        $response->assertSee('SADAKA JARIYA');
        $response->assertSee('Sahih Muslim');
        $response->assertSee('Fomu ya Mchango');
    }

    public function test_sadaka_alias_redirects_to_support_page(): void
    {
        $response = $this->get('/sadaka');

        $response->assertRedirect(route('support'));
    }

    public function test_guest_can_initiate_tzs_donation(): void
    {
        Livewire::test(SupportIndex::class)
            ->set('currency', 'TZS')
            ->set('selectedPreset', 10000)
            ->set('paymentMethod', 'mobile_money')
            ->set('donorName', 'Hassan Ali')
            ->set('donorEmail', 'hassan@example.com')
            ->set('donorPhone', '0712345678')
            ->set('message', 'Allah akubalie kazi hii njema.')
            ->call('initiatePayment')
            ->assertHasNoErrors()
            ->assertSet('checkoutInitiated', true)
            ->assertSet('activeAmount', 10000);

        $this->assertDatabaseHas('payments', [
            'donor_name' => 'Hassan Ali',
            'donor_email' => 'hassan@example.com',
            'donor_phone' => '0712345678',
            'amount' => 10000.00,
            'currency' => 'TZS',
            'purpose' => 'sadaka_jariya',
            'payment_method' => 'mobile_money',
            'status' => 'pending',
            'is_anonymous' => false,
        ]);
    }

    public function test_anonymous_donation_masks_name_in_database(): void
    {
        Livewire::test(SupportIndex::class)
            ->set('currency', 'TZS')
            ->set('selectedPreset', 25000)
            ->set('donorName', 'Siri Yangu')
            ->set('isAnonymous', true)
            ->call('initiatePayment')
            ->assertHasNoErrors()
            ->assertSet('checkoutInitiated', true);

        $this->assertDatabaseHas('payments', [
            'donor_name' => null,
            'amount' => 25000.00,
            'is_anonymous' => true,
        ]);
    }

    public function test_custom_amount_and_usd_currency_work(): void
    {
        Livewire::test(SupportIndex::class)
            ->call('switchCurrency', 'USD')
            ->assertSet('currency', 'USD')
            ->assertSet('selectedPreset', 5)
            ->set('customAmount', '75')
            ->set('purpose', 'supporter_membership')
            ->call('initiatePayment')
            ->assertHasNoErrors()
            ->assertSet('checkoutInitiated', true)
            ->assertSet('activeAmount', 75);

        $this->assertDatabaseHas('payments', [
            'amount' => 75.00,
            'currency' => 'USD',
            'purpose' => 'supporter_membership',
            'tier' => 'gold',
        ]);
    }

    public function test_donation_amount_below_minimum_fails_validation(): void
    {
        Livewire::test(SupportIndex::class)
            ->set('currency', 'TZS')
            ->set('customAmount', '500')
            ->call('initiatePayment')
            ->assertHasErrors(['amount'])
            ->assertSet('checkoutInitiated', false);
    }

    public function test_authenticated_user_links_id_and_becomes_supporter_on_completion(): void
    {
        $user = User::factory()->create([
            'name' => 'Fatma Juma',
            'email' => 'fatma@example.com',
            'is_supporter' => false,
        ]);

        $this->actingAs($user);

        Livewire::test(SupportIndex::class)
            ->set('selectedPreset', 50000)
            ->set('purpose', 'supporter_membership')
            ->call('initiatePayment')
            ->assertHasNoErrors();

        $payment = Payment::where('user_id', $user->id)->first();
        $this->assertNotNull($payment);
        $this->assertEquals('pending', $payment->status);

        // Mark payment as completed (simulating webhook arrival)
        $payment->markAsCompleted('PESAPAL-TEST-9988');

        $this->assertEquals('completed', $payment->fresh()->status);
        $this->assertEquals('PESAPAL-TEST-9988', $payment->fresh()->gateway_reference);

        $user->refresh();
        $this->assertTrue($user->isSupporter());
        $this->assertEquals('gold', $user->supporter_tier);
        $this->assertNotNull($user->supporter_since);
        $this->assertEquals('Msaidizi Mkuu (Dhahabu)', $user->supporterBadge());
    }

    public function test_public_donations_appear_on_support_wall(): void
    {
        $public = Payment::create([
            'reference' => 'SADAKA-20261019-001',
            'donor_name' => 'Rashid Bakari',
            'amount' => 10000,
            'currency' => 'TZS',
            'purpose' => 'sadaka_jariya',
            'gateway' => 'clickpesa',
            'status' => 'completed',
            'is_anonymous' => false,
            'message' => 'Mwenyezi Mungu awajaze kheri.',
            'completed_at' => now(),
        ]);

        $anon = Payment::create([
            'reference' => 'SADAKA-20261019-002',
            'donor_name' => 'Mtu Asiyejulikana',
            'amount' => 50000,
            'currency' => 'TZS',
            'purpose' => 'sadaka_jariya',
            'gateway' => 'clickpesa',
            'status' => 'completed',
            'is_anonymous' => true,
            'message' => 'Sadaka ya siri',
            'completed_at' => now(),
        ]);

        $this->assertTrue(Payment::publicDonations()->where('id', $public->id)->exists());
        $this->assertFalse(Payment::publicDonations()->where('id', $anon->id)->exists());
    }

    public function test_process_checkout_redirects_to_simulation_in_simulation_mode(): void
    {
        Livewire::test(SupportIndex::class)
            ->set('selectedPreset', 10000)
            ->set('donorName', 'Saida Salim')
            ->call('initiatePayment')
            ->assertSet('checkoutInitiated', true)
            ->call('processCheckout')
            ->assertRedirect();
    }

    public function test_simulation_page_renders_with_order_details(): void
    {
        $payment = Payment::create([
            'reference' => 'SADAKA-20261019-SIM01',
            'gateway' => 'simulation',
            'amount' => 15000,
            'currency' => 'TZS',
            'purpose' => 'sadaka_jariya',
            'status' => 'pending',
            'donor_name' => 'Ali Juma',
        ]);

        $response = $this->get(route('payments.simulate', ['reference' => $payment->reference]));

        $response->assertStatus(200);
        $response->assertSee('SADAKA-20261019-SIM01');
        $response->assertSee('TZS 15,000');
        $response->assertSee('Ali Juma');
        $response->assertSee('M-Pesa (Vodacom)');
    }

    public function test_payment_callback_completes_payment_and_redirects_to_receipt(): void
    {
        $user = User::factory()->create([
            'email' => 'ali@example.com',
            'is_supporter' => false,
        ]);

        $payment = Payment::create([
            'user_id' => $user->id,
            'reference' => 'SADAKA-20261019-CB01',
            'gateway' => 'simulation',
            'gateway_reference' => 'SIM-TRACKING-123',
            'amount' => 20000,
            'currency' => 'TZS',
            'tier' => 'silver',
            'purpose' => 'supporter_membership',
            'status' => 'pending',
            'donor_name' => 'Ali Mwinyi',
        ]);

        $response = $this->get(route('payments.callback', [
            'reference' => $payment->reference,
            'OrderTrackingId' => 'SIM-TRACKING-123',
            'simulation_action' => 'complete',
            'method' => 'mpesa',
        ]));

        $response->assertRedirect(route('payments.receipt', ['reference' => $payment->reference]));
        $this->assertEquals('completed', $payment->fresh()->status);
        $this->assertEquals('mpesa', $payment->fresh()->payment_method);

        $user->refresh();
        $this->assertTrue($user->is_supporter);
        $this->assertEquals('silver', $user->supporter_tier);
        $this->assertEquals('Msaidizi Mwaminifu (Fedha)', $user->supporterBadge());
    }

    public function test_payment_callback_cancel_marks_payment_as_failed(): void
    {
        $payment = Payment::create([
            'reference' => 'SADAKA-20261019-CN01',
            'gateway' => 'simulation',
            'amount' => 10000,
            'currency' => 'TZS',
            'purpose' => 'sadaka_jariya',
            'status' => 'pending',
        ]);

        $response = $this->get(route('payments.callback', [
            'reference' => $payment->reference,
            'simulation_action' => 'cancel',
        ]));

        $response->assertRedirect(route('payments.receipt', ['reference' => $payment->reference]));
        $this->assertEquals('failed', $payment->fresh()->status);
    }

    public function test_payment_ipn_webhook_handles_pesapal_notification(): void
    {
        $payment = Payment::create([
            'reference' => 'SADAKA-20261019-IPN01',
            'gateway_reference' => 'PESAPAL-TRACKING-7788',
            'amount' => 50000,
            'currency' => 'TZS',
            'tier' => 'gold',
            'status' => 'pending',
        ]);

        $response = $this->get(route('payments.ipn', [
            'OrderNotificationType' => 'IPNCHANGE',
            'OrderTrackingId' => 'PESAPAL-TRACKING-7788',
            'OrderMerchantReference' => 'SADAKA-20261019-IPN01',
        ]));

        $response->assertStatus(200);
        $response->assertJson([
            'orderNotificationType' => 'IPNCHANGE',
            'orderTrackingId' => 'PESAPAL-TRACKING-7788',
            'orderMerchantReference' => 'SADAKA-20261019-IPN01',
            'status' => '200',
        ]);
    }

    public function test_receipt_page_displays_official_details_and_quran_verse(): void
    {
        $payment = Payment::create([
            'reference' => 'SADAKA-20261019-REC01',
            'amount' => 10000,
            'currency' => 'TZS',
            'purpose' => 'sadaka_jariya',
            'status' => 'completed',
            'donor_name' => 'Khadija Said',
            'message' => 'Namuombea mzazi wangu rehema.',
        ]);

        $response = $this->get(route('payments.receipt', ['reference' => $payment->reference]));

        $response->assertStatus(200);
        $response->assertSee('SADAKA-20261019-REC01');
        $response->assertSee('TZS 10,000');
        $response->assertSee('Khadija Said');
        $response->assertSee('Namuombea mzazi wangu rehema.');
        $response->assertSee('مَّثَلُ الَّذِينَ يُنفِقُونَ أَمْوَالَهُمْ');
        $response->assertSee('MALIPO YAMETHIBITISHWA');
    }
}

