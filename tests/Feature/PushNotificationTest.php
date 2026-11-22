<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Chapter;
use App\Models\Collection;
use App\Models\Hadith;
use App\Models\PushSubscription;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PushNotificationTest extends TestCase
{
    use RefreshDatabase;

    private function createPublishedHadith(): Hadith
    {
        $collection = Collection::create([
            'slug' => 'bukhari',
            'name' => 'Sahih al-Bukhari',
            'name_ar' => 'صحيح البخاري',
        ]);
        $book = Book::create([
            'collection_id' => $collection->id,
            'number' => 1,
            'title_sw' => 'Kitabu cha Wahyi',
            'title_ar' => 'كتاب بدء الوحي',
        ]);
        $chapter = Chapter::create([
            'book_id' => $book->id,
            'number' => 1,
            'title_sw' => 'Mlango wa kwanza',
            'title_ar' => 'باب',
        ]);

        return Hadith::create([
            'chapter_id' => $chapter->id,
            'reference' => 'bukhari:test:1',
            'number' => '1',
            'arabic' => 'إِنَّمَا الأَعْمَالُ بِالنِّيَّاتِ',
            'swahili' => 'Hakika matendo yote yanategemea nia.',
            'english' => 'Actions are but by intentions.',
            'source_name' => 'Test Source',
            'source_url' => 'https://example.test/hadith/1',
            'numbering_system' => 'Test',
            'translator' => 'Test Translator',
            'translation_source_url' => 'https://example.test/sw/1',
            'license' => 'CC-BY-4.0',
            'reviewed_by' => 'Test Reviewer',
            'reviewed_at' => '2026-01-01',
            'is_published' => true,
        ]);
    }

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
        $this->createPublishedHadith();

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
        $this->createPublishedHadith();

        PushSubscription::create([
            'endpoint' => 'https://fcm.googleapis.com/fcm/send/cli-token-test',
            'is_active' => true,
        ]);

        $this->artisan('hadith:notify-daily')->assertSuccessful();

        $sub = PushSubscription::where('endpoint', 'https://fcm.googleapis.com/fcm/send/cli-token-test')->first();
        $this->assertNotNull($sub->last_notified_at);
    }
}
