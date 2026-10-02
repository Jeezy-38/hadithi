<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_forgot_sends_swahili_reset_link_and_shows_confirmation(): void
    {
        Notification::fake();
        $user = User::factory()->create(['email' => 'amina@example.com']);

        $this->from('/')->post('/auth/forgot', ['email' => ' Amina@Example.com '])
            ->assertRedirect('/')
            ->assertSessionHas('auth_forgot_sent', 'amina@example.com');

        Notification::assertSentTo($user, ResetPassword::class, function (ResetPassword $notification) use ($user) {
            $mail = $notification->toMail($user);
            $this->assertSame('Weka nenosiri jipya', $mail->actionText);
            $this->assertStringContainsString('/auth/reset/'.$notification->token, $mail->actionUrl);

            return true;
        });

        $this->followingRedirects()->from('/')->post('/auth/forgot', ['email' => 'amina@example.com'])
            ->assertSee('data-auth-mode="forgot"', false)
            ->assertSee('Angalia email yako');
    }

    public function test_unknown_email_gets_same_response(): void
    {
        Notification::fake();

        $this->from('/')->post('/auth/forgot', ['email' => 'hayupo@example.com'])
            ->assertRedirect('/')
            ->assertSessionHas('auth_forgot_sent');

        Notification::assertNothingSent();
    }

    public function test_forgot_validates_email(): void
    {
        $this->from('/')->post('/auth/forgot', ['email' => 'si-email'])
            ->assertSessionHasErrors('email', null, 'forgot');
    }

    public function test_reset_page_renders(): void
    {
        $this->get('/auth/reset/abc123?email=amina@example.com')
            ->assertOk()
            ->assertSee('Weka nenosiri jipya')
            ->assertSee('value="abc123"', false)
            ->assertSee('amina@example.com');
    }

    public function test_user_can_reset_password_and_is_logged_in(): void
    {
        $user = User::factory()->unverified()->create(['email' => 'amina@example.com', 'password' => 'zamani123']);
        $token = Password::createToken($user);

        $this->post('/auth/reset', [
            'token' => $token, 'email' => 'amina@example.com', 'password' => 'jipya2026',
        ])->assertRedirect(route('library'))->assertSessionHas('auth_status');

        $user->refresh();
        $this->assertTrue(Hash::check('jipya2026', $user->password));
        $this->assertNotNull($user->email_verified_at);
        $this->assertAuthenticatedAs($user);
    }

    public function test_google_only_user_can_set_a_password(): void
    {
        $user = User::factory()->create(['email' => 'amina@example.com']);
        $user->forceFill(['password' => null])->save();
        $token = Password::createToken($user);

        $this->post('/auth/reset', ['token' => $token, 'email' => 'amina@example.com', 'password' => 'jipya2026'])
            ->assertRedirect(route('library'));

        $this->assertTrue(Hash::check('jipya2026', $user->fresh()->password));
    }

    public function test_invalid_token_is_rejected(): void
    {
        $user = User::factory()->create(['email' => 'amina@example.com', 'password' => 'zamani123']);

        $this->from('/auth/reset/bad')->post('/auth/reset', [
            'token' => 'bad', 'email' => 'amina@example.com', 'password' => 'jipya2026',
        ])->assertRedirect('/auth/reset/bad')->assertSessionHasErrors('email');

        $this->assertTrue(Hash::check('zamani123', $user->fresh()->password));
        $this->assertGuest();
    }

    public function test_weak_new_password_is_rejected(): void
    {
        $user = User::factory()->create(['email' => 'amina@example.com']);
        $token = Password::createToken($user);

        $this->post('/auth/reset', ['token' => $token, 'email' => 'amina@example.com', 'password' => 'fupi'])
            ->assertSessionHasErrors('password');
    }
}
