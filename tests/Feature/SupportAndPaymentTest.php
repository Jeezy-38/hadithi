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
        Payment::create([
            'reference' => 'SADAKA-20261019-001',
            'donor_name' => 'Rashid Bakari',
            'amount' => 10000,
            'currency' => 'TZS',
            'purpose' => 'sadaka_jariya',
            'status' => 'completed',
            'is_anonymous' => false,
            'message' => 'Mwenyezi Mungu awajaze kheri.',
            'completed_at' => now(),
        ]);

        Payment::create([
            'reference' => 'SADAKA-20261019-002',
            'donor_name' => 'Mtu Asiyejulikana',
            'amount' => 50000,
            'currency' => 'TZS',
            'purpose' => 'sadaka_jariya',
            'status' => 'completed',
            'is_anonymous' => true,
            'message' => 'Sadaka ya siri',
            'completed_at' => now(),
        ]);

        $response = $this->get(route('support'));

        $response->assertSee('Rashid Bakari');
        $response->assertSee('Mwenyezi Mungu awajaze kheri.');
        // Anonymous donation message shouldn't leak the raw name
        $response->assertDontSee('Mtu Asiyejulikana');
    }
}
