<?php

namespace Tests\Feature;

use App\Models\Hadith;
use App\Models\PushSubscription;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PushNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_subscribe_to_push_notifications(): void
    {
        $response = $this->postJson(route('api.push.subscribe'), [
            'endpoint' => 'https://fcm.googleapis.com/fcm/send/test-guest-token-123',
            'keys' => [
                'p256dh' => 'mock-p256dh-key',
                'auth' => 'mock-auth-token',
            ],
        ]);

        $response->assertOk()
            ->assertJsonPath('status', 'success');

        $this->assertDatabaseHas('push_subscriptions', [
            'endpoint' => 'https://fcm.googleapis.com/fcm/send/test-guest-token-123',
            'is_active' => true,
            'user_id' => null,
        ]);
    }

    public function test_authenticated_user_links_id_to_push_subscription(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson(route('api.push.subscribe'), [
            'endpoint' => 'https://fcm.googleapis.com/fcm/send/test-user-token-456',
        ]);

        $response->assertOk();

        $this->assertDatabaseHas('push_subscriptions', [
            'endpoint' => 'https://fcm.googleapis.com/fcm/send/test-user-token-456',
            'user_id' => $user->id,
            'is_active' => true,
        ]);
    }

    public function test_user_can_unsubscribe_from_push_notifications(): void
    {
        PushSubscription::create([
            'endpoint' => 'https://fcm.googleapis.com/fcm/send/to-be-disabled',
            'is_active' => true,
        ]);

        $response = $this->deleteJson(route('api.push.unsubscribe'), [
            'endpoint' => 'https://fcm.googleapis.com/fcm/send/to-be-disabled',
        ]);

        $response->assertOk();

        $this->assertDatabaseHas('push_subscriptions', [
            'endpoint' => 'https://fcm.googleapis.com/fcm/send/to-be-disabled',
            'is_active' => false,
        ]);
    }

    public function test_today_hadith_api_returns_structured_payload(): void
    {
        $this->seed();
        $file = database_path('data/hadith-starter.json');
        $this->artisan('hadith:import', ['file' => $file])->assertSuccessful();

        $response = $this->getJson(route('api.hadith.today'));

        $response->assertOk()
            ->assertJsonStructure([
                'id',
                'title',
                'body',
                'arabic',
                'swahili',
                'reference',
                'url',
                'icon',
                'badge',
            ]);
    }

    public function test_artisan_daily_notification_command_runs_successfully(): void
    {
        $this->seed();
        $file = database_path('data/hadith-starter.json');
        $this->artisan('hadith:import', ['file' => $file])->assertSuccessful();

        PushSubscription::create([
            'endpoint' => 'https://fcm.googleapis.com/fcm/send/cli-token-test',
            'is_active' => true,
        ]);

        $this->artisan('hadith:notify-daily')->assertSuccessful();

        $sub = PushSubscription::where('endpoint', 'https://fcm.googleapis.com/fcm/send/cli-token-test')->first();
        $this->assertNotNull($sub->last_notified_at);
    }
}
