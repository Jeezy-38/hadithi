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
        $response->assertSee('Jopo Kuu la Utawala (Admin)');
        $response->assertSee('Msimamizi Mkuu');
        $response->assertSee('TEST-REF-001');
        $response->assertSee('Salum Ali');
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

        // Search test
        $searchResponse = $this->actingAs($admin)->get(route('admin.dashboard', ['search' => 'Bakari']));
        $searchResponse->assertSee('SADAKA-ALPHA');
        $searchResponse->assertDontSee('SADAKA-BETA');

        // Status filter test
        $filterResponse = $this->actingAs($admin)->get(route('admin.dashboard', ['status' => 'pending']));
        $filterResponse->assertSee('SADAKA-BETA');
        $filterResponse->assertDontSee('SADAKA-ALPHA');
    }

    public function test_admin_can_export_payments_csv(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        Payment::create([
            'reference' => 'CSV-REF-999',
            'donor_name' => 'Mchangiaji Mtihani',
            'amount' => 75000,
            'currency' => 'TZS',
            'status' => 'completed',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.payments.export'));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'text/csv; charset=utf-8');
        $this->assertStringContainsString('attachment; filename="malipo_hadith_app_', $response->headers->get('Content-Disposition'));
        
        $content = $response->streamedContent();
        $this->assertStringContainsString('Namba ya Kumbukumbu', $content);
        $this->assertStringContainsString('CSV-REF-999', $content);
        $this->assertStringContainsString('Mchangiaji Mtihani', $content);
    }

    public function test_track_page_view_middleware_records_visits(): void
    {
        $this->get(route('library'));

        $this->assertTrue(PageView::where('path', '/')->exists());
    }

    public function test_make_admin_artisan_command(): void
    {
        $user = User::factory()->create([
            'email' => 'admin.test@example.com',
            'is_admin' => false,
        ]);

        $this->artisan('app:make-admin', ['email' => 'admin.test@example.com'])
            ->expectsOutputToContain('sasa ni ADMIN kikamilifu')
            ->assertSuccessful();

        $this->assertTrue($user->fresh()->is_admin);

        // Revoke test
        $this->artisan('app:make-admin', ['email' => 'admin.test@example.com', '--revoke' => true])
            ->expectsOutputToContain('Haki za admin zimeondolewa')
            ->assertSuccessful();

        $this->assertFalse($user->fresh()->is_admin);
    }
}
