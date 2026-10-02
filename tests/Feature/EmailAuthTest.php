<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class EmailAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_register(): void
    {
        $this->from('/')->post('/auth/register', [
            'name' => 'Amina Juma',
            'email' => '  Amina@Example.com ',
            'password' => 'salama123',
        ])->assertRedirect('/')->assertSessionHas('auth_status');

        $user = User::firstWhere('email', 'amina@example.com');
        $this->assertNotNull($user);
        $this->assertTrue(Hash::check('salama123', $user->password));
        $this->assertNull($user->email_verified_at);
        $this->assertAuthenticatedAs($user);
    }

    public function test_register_validates_and_reopens_drawer_on_register_tab(): void
    {
        $this->from('/')->post('/auth/register', ['name' => '', 'email' => 'si-email', 'password' => 'fupi'])
            ->assertRedirect('/')
            ->assertSessionHasErrors(['name', 'email', 'password'], null, 'register');

        $this->assertGuest();

        $this->followingRedirects()->from('/')->post('/auth/register', ['name' => 'A', 'email' => 'x', 'password' => 'y'])
            ->assertSee('data-auto-open', false)
            ->assertSee('data-auth-mode="register"', false);
    }

    public function test_register_rejects_existing_email(): void
    {
        User::factory()->create(['email' => 'amina@example.com']);

        $this->from('/')->post('/auth/register', [
            'name' => 'Mwingine', 'email' => 'amina@example.com', 'password' => 'salama123',
        ])->assertSessionHasErrors('email', null, 'register');

        $this->assertSame(1, User::count());
    }

    public function test_user_can_login_with_password(): void
    {
        $user = User::factory()->create(['email' => 'amina@example.com', 'password' => 'salama123']);

        $this->from('/hadith-ya-leo')->post('/auth/login', [
            'email' => 'AMINA@example.com', 'password' => 'salama123', 'remember' => '1',
        ])->assertRedirect('/hadith-ya-leo');

        $this->assertAuthenticatedAs($user);
    }

    public function test_wrong_password_fails_on_login_tab(): void
    {
        User::factory()->create(['email' => 'amina@example.com', 'password' => 'salama123']);

        $this->from('/')->post('/auth/login', ['email' => 'amina@example.com', 'password' => 'kosa999'])
            ->assertSessionHasErrors('email', null, 'login');

        $this->assertGuest();
    }

    public function test_google_only_account_gets_helpful_message(): void
    {
        $user = User::factory()->create(['email' => 'amina@example.com']);
        $user->forceFill(['password' => null])->save();

        $this->from('/')->post('/auth/login', ['email' => 'amina@example.com', 'password' => 'chochote1'])
            ->assertSessionHasErrors(['email' => 'Akaunti hii ilifunguliwa kwa Google. Tumia kitufe cha "Endelea na Google".'], null, 'login');
    }

    public function test_login_is_rate_limited(): void
    {
        User::factory()->create(['email' => 'amina@example.com', 'password' => 'salama123']);

        foreach (range(1, 5) as $i) {
            $this->from('/')->post('/auth/login', ['email' => 'amina@example.com', 'password' => 'kosa'.$i]);
        }

        $this->from('/')->post('/auth/login', ['email' => 'amina@example.com', 'password' => 'salama123']);
        $this->assertGuest();
        $this->assertStringContainsString('Umejaribu mara nyingi', session('errors')->getBag('login')->first('email'));
    }

    public function test_logged_in_user_cannot_post_register(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post('/auth/register', [
            'name' => 'X', 'email' => 'new@example.com', 'password' => 'salama123',
        ])->assertRedirect();

        $this->assertSame(1, User::count());
    }
}
