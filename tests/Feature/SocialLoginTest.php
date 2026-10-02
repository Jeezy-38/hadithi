<?php

namespace Tests\Feature;

use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;
use Tests\TestCase;

class SocialLoginTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        foreach (['google', 'facebook', 'x'] as $provider) {
            config([
                "services.$provider.client_id" => 'test-id',
                "services.$provider.client_secret" => 'test-secret',
                "services.$provider.redirect" => "http://localhost/auth/$provider/callback",
            ]);
        }
    }

    private function fakeProviderUser(string $id = '123', ?string $email = 'amina@example.com', array $raw = ['email_verified' => true]): void
    {
        $user = (new SocialiteUser)->setRaw($raw)->map([
            'id' => $id,
            'name' => 'Amina Juma',
            'nickname' => 'amina',
            'email' => $email,
            'avatar' => 'https://example.com/a.png',
        ]);

        Socialite::shouldReceive('driver->user')->andReturn($user);
    }

    public function test_layout_renders_login_drawer_for_guests(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('id="auth-drawer"', false)
            ->assertSee(route('auth.redirect', 'google'), false)
            ->assertSee(route('auth.register'), false)
            ->assertSee(route('auth.login'), false)
            ->assertDontSee(route('auth.redirect', 'facebook'), false)
            ->assertDontSee(route('auth.redirect', 'x'), false);
    }

    public function test_login_route_opens_drawer(): void
    {
        $this->get('/login')->assertRedirect(route('library', ['login' => 1]));
        $this->get('/?login=1')->assertSee('data-auto-open', false);
    }

    public function test_unknown_provider_is_404(): void
    {
        $this->get('/auth/myspace/redirect')->assertNotFound();
    }

    public function test_redirect_goes_to_google(): void
    {
        $response = $this->get('/auth/google/redirect');

        $response->assertRedirect();
        $this->assertStringStartsWith('https://accounts.google.com/', $response->headers->get('Location'));
    }

    public function test_unconfigured_provider_returns_friendly_error(): void
    {
        config(['services.facebook.client_id' => null]);

        $this->get('/auth/facebook/redirect')
            ->assertRedirect(route('library', ['login' => 1]))
            ->assertSessionHas('auth_error');
    }

    public function test_callback_creates_user_and_logs_in(): void
    {
        $this->fakeProviderUser();

        $this->get('/auth/google/callback?code=abc&state=xyz')->assertRedirect(route('library'));

        $user = User::firstWhere('email', 'amina@example.com');
        $this->assertNotNull($user);
        $this->assertAuthenticatedAs($user);
        $this->assertNull($user->password);
        $this->assertDatabaseHas('social_accounts', ['user_id' => $user->id, 'provider' => 'google', 'provider_id' => '123']);
    }

    public function test_returning_user_reuses_account(): void
    {
        $this->fakeProviderUser();
        $this->get('/auth/google/callback?code=abc');
        auth()->logout();
        $this->get('/auth/google/callback?code=def');

        $this->assertSame(1, User::count());
        $this->assertSame(1, SocialAccount::count());
    }

    public function test_verified_email_links_existing_user(): void
    {
        $existing = User::factory()->create(['email' => 'amina@example.com']);
        $this->fakeProviderUser(id: '999');

        $this->get('/auth/facebook/callback?code=abc');

        $this->assertAuthenticatedAs($existing);
        $this->assertSame(1, User::count());
    }

    public function test_google_login_wipes_password_of_unverified_email_account(): void
    {
        // Mtu mwingine alijisajili kwa email ya Amina (haijathibitishwa); Amina anaingia kwa Google.
        $squatter = User::factory()->unverified()->create(['email' => 'amina@example.com', 'password' => 'mvamizi123']);
        $this->fakeProviderUser();

        $this->get('/auth/google/callback?code=abc');

        $this->assertAuthenticatedAs($squatter);
        $squatter->refresh();
        $this->assertNull($squatter->password);
        $this->assertNotNull($squatter->email_verified_at);
    }

    public function test_unverified_google_email_does_not_take_over_existing_user(): void
    {
        User::factory()->create(['email' => 'amina@example.com']);
        $this->fakeProviderUser(raw: ['email_verified' => false]);

        $this->get('/auth/google/callback?code=abc');

        $this->assertSame(2, User::count());
        $this->assertNull(auth()->user()->email);
    }

    public function test_x_user_without_email_can_sign_in(): void
    {
        $this->fakeProviderUser(email: null, raw: []);

        $this->get('/auth/x/callback?code=abc')->assertRedirect(route('library'));

        $this->assertAuthenticated();
        $this->assertNull(auth()->user()->email);
    }

    public function test_cancelled_login_shows_error(): void
    {
        $this->get('/auth/google/callback?error=access_denied')
            ->assertRedirect(route('library', ['login' => 1]))
            ->assertSessionHas('auth_error');
        $this->assertGuest();
    }

    public function test_logged_in_user_sees_profile_and_can_logout(): void
    {
        $user = User::factory()->create(['name' => 'Amina Juma']);

        $this->actingAs($user)->get('/')->assertSee('Amina Juma')->assertSee(route('logout'), false);
        $this->actingAs($user)->post('/logout')->assertRedirect(route('library'));
        $this->assertGuest();
    }
}
