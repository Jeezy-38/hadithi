<?php

namespace Tests\Feature;

use App\Models\PageView;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_away_from_admin_dashboard(): void
    {
        $response = $this->get(route('admin.dashboard'));

        $response->assertRedirect(route('login'));
    }

    public function test_regular_user_cannot_access_admin_dashboard(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $response = $this->actingAs($user)->get(route('admin.dashboard'));

        $response->assertStatus(403);
    }

    public function test_admin_user_can_access_dashboard_and_see_metrics(): void
    {
        $admin = User::factory()->create(['is_admin' => true, 'name' => 'Msimamizi Mkuu']);

        // Create some sample payments
        Payment::create([
            'reference' => 'TEST-REF-001',
            'donor_name' => 'Salum Ali',
            'amount' => 50000,
            'currency' => 'TZS',
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        Payment::create([
            'reference' => 'TEST-REF-002',
            'donor_name' => 'Fatma Juma',
            'amount' => 20000,
            'currency' => 'TZS',
            'status' => 'pending',
        ]);

        // Create sample page views
        PageView::create([
            'path' => '/hadith/1',
            'ip_hash' => 'hash1',
            'device_type' => 'mobile',
            'viewed_date' => now()->toDateString(),
        ]);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertSee('Jopo Kuu la Utawala');
        $response->assertSee('Msimamizi Mkuu');
        $response->assertSee('50,000');
        $response->assertSee('/hadith/1');
    }

    public function test_admin_can_filter_and_search_payments(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        Payment::create([
            'reference' => 'SADAKA-ALPHA',
            'donor_name' => 'Bakari Hassan',
            'amount' => 15000,
            'currency' => 'TZS',
            'status' => 'completed',
        ]);

        Payment::create([
            'reference' => 'SADAKA-BETA',
            'donor_name' => 'Zainab Rashid',
            'amount' => 10000,
            'currency' => 'TZS',
            'status' => 'pending',
        ]);

        // Search test on payments tab
        $searchResponse = $this->actingAs($admin)->get(route('admin.dashboard', ['tab' => 'payments', 'payment_search' => 'Bakari']));
        $searchResponse->assertSee('SADAKA-ALPHA');
        $searchResponse->assertDontSee('SADAKA-BETA');

        // Status filter test
        $filterResponse = $this->actingAs($admin)->get(route('admin.dashboard', ['tab' => 'payments', 'payment_status' => 'pending']));
        $filterResponse->assertSee('SADAKA-BETA');
        $filterResponse->assertDontSee('SADAKA-ALPHA');
    }

    public function test_admin_can_view_users_tab_and_search(): void
    {
        $admin = User::factory()->create(['is_admin' => true, 'name' => 'Super Admin', 'email' => 'super@example.com']);
        $user1 = User::factory()->create(['name' => 'Kassim Majaliwa', 'email' => 'kassim@example.com']);
        $user2 = User::factory()->create(['name' => 'Amina Hamis', 'email' => 'amina@example.com']);

        $response = $this->actingAs($admin)->get(route('admin.dashboard', ['tab' => 'users', 'user_search' => 'Kassim']));

        $response->assertOk();
        $response->assertSee('Kassim Majaliwa');
        $response->assertSee('kassim@example.com');
        $response->assertDontSee('Amina Hamis');
    }

    public function test_admin_can_toggle_user_admin_status(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $targetUser = User::factory()->create(['is_admin' => false]);

        $response = $this->actingAs($admin)->post(route('admin.users.toggle-admin', $targetUser));

        $response->assertRedirect();
        $targetUser->refresh();
        $this->assertTrue($targetUser->is_admin);

        // Revoke
        $this->actingAs($admin)->post(route('admin.users.toggle-admin', $targetUser));
        $targetUser->refresh();
        $this->assertFalse($targetUser->is_admin);
    }

    public function test_admin_cannot_demote_themselves(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($admin)->post(route('admin.users.toggle-admin', $admin));

        $response->assertRedirect();
        $admin->refresh();
        $this->assertTrue($admin->is_admin);
    }

    public function test_admin_can_toggle_supporter_status(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $user = User::factory()->create(['is_supporter' => false]);

        $this->actingAs($admin)->post(route('admin.users.toggle-supporter', $user));

        $user->refresh();
        $this->assertTrue($user->is_supporter);
    }

    public function test_admin_can_export_payments_csv(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        Payment::create([
            'reference' => 'CSV-001',
            'donor_name' => 'Juma Shaban',
            'amount' => 5000,
            'currency' => 'TZS',
            'status' => 'completed',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.payments.export'));

        $response->assertOk();
        $response->assertHeader('content-type', 'text/csv; charset=utf-8');
    }

    public function test_track_page_view_middleware_records_visits(): void
    {
        $this->get(route('library'));

        $this->assertDatabaseHas('page_views', [
            'path' => '/',
        ]);
    }

    public function test_make_admin_artisan_command(): void
    {
        $user = User::factory()->create(['email' => 'tahira@example.com', 'is_admin' => false]);

        $this->artisan('app:make-admin tahira@example.com')
            ->assertSuccessful();

        $user->refresh();
        $this->assertTrue($user->is_admin);
    }
}
