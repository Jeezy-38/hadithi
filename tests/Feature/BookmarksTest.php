<?php

namespace Tests\Feature;

use App\Models\{Book, Chapter, Collection, Hadith, User};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookmarksTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    private function record(string $collection = 'bukhari', string $number = '1', bool $published = true): Hadith
    {
        $book = Book::firstOrCreate(
            ['collection_id' => Collection::where('slug', $collection)->value('id'), 'number' => 1],
            ['title_sw' => 'Kitabu cha majaribio', 'title_ar' => 'كتاب الاختبار']
        );
        $chapter = Chapter::firstOrCreate(
            ['book_id' => $book->id, 'number' => 1],
            ['title_sw' => 'Mlango wa majaribio', 'title_ar' => 'باب الاختبار']
        );

        return Hadith::create([
            'chapter_id' => $chapter->id,
            'reference' => "$collection:test:$number",
            'number' => $number,
            'english' => 'Synthetic English',
            'arabic' => 'نَصٌّ لِلاخْتِبَارِ',
            'swahili' => "Rekodi ya majaribio $collection $number",
            'source_name' => 'Synthetic test',
            'source_url' => 'https://example.com/arabic',
            'numbering_system' => 'Test',
            'translator' => 'Test',
            'translation_source_url' => 'https://example.com/swahili',
            'license' => 'Test',
            'reviewed_by' => 'Tester',
            'reviewed_at' => '2026-01-01',
            'is_published' => $published,
        ]);
    }

    public function test_guest_can_fetch_bookmarks_by_ids_query(): void
    {
        $h1 = $this->record('bukhari', '10');
        $h2 = $this->record('muslim', '20');

        $response = $this->getJson("/vipendwa/data?ids={$h1->id},{$h2->id}");

        $response->assertOk()
            ->assertJsonCount(2)
            ->assertJsonPath('0.id', $h1->id)
            ->assertJsonPath('1.id', $h2->id);
    }

    public function test_guest_ids_endpoint_returns_empty_array(): void
    {
        $response = $this->getJson('/vipendwa/ids');

        $response->assertOk()
            ->assertJson([
                'authenticated' => false,
                'ids' => [],
            ]);
    }

    public function test_authenticated_user_can_toggle_bookmark(): void
    {
        $user = User::factory()->create();
        $hadith = $this->record('bukhari', '15');

        // First toggle: bookmarks the hadith
        $this->actingAs($user)
            ->postJson("/vipendwa/toggle/{$hadith->id}")
            ->assertOk()
            ->assertJson(['bookmarked' => true, 'count' => 1]);

        $this->assertTrue($user->bookmarks()->where('hadith_id', $hadith->id)->exists());

        // Second toggle: un-bookmarks the hadith
        $this->actingAs($user)
            ->postJson("/vipendwa/toggle/{$hadith->id}")
            ->assertOk()
            ->assertJson(['bookmarked' => false, 'count' => 0]);

        $this->assertFalse($user->bookmarks()->where('hadith_id', $hadith->id)->exists());
    }

    public function test_unauthenticated_toggle_is_rejected(): void
    {
        $hadith = $this->record('bukhari', '1');

        $this->postJson("/vipendwa/toggle/{$hadith->id}")
            ->assertUnauthorized();
    }

    public function test_authenticated_user_bookmarks_are_returned_without_ids_query(): void
    {
        $user = User::factory()->create();
        $h1 = $this->record('bukhari', '101');
        $h2 = $this->record('muslim', '102');

        $user->bookmarks()->attach([$h1->id, $h2->id]);

        $this->actingAs($user)
            ->getJson('/vipendwa/data')
            ->assertOk()
            ->assertJsonCount(2);

        $this->actingAs($user)
            ->getJson('/vipendwa/ids')
            ->assertOk()
            ->assertJson([
                'authenticated' => true,
                'ids' => [$h1->id, $h2->id],
            ]);
    }

    public function test_guest_bookmarks_passed_by_authenticated_user_are_auto_synced(): void
    {
        $user = User::factory()->create();
        $h1 = $this->record('bukhari', '201');
        $h2 = $this->record('muslim', '202');

        // User already has h1 in database
        $user->bookmarks()->attach($h1->id);

        // Client passes guest ids including h2
        $this->actingAs($user)
            ->getJson("/vipendwa/data?ids={$h2->id}")
            ->assertOk()
            ->assertJsonCount(2);

        $this->assertTrue($user->bookmarks()->where('hadith_id', $h2->id)->exists());
    }

    public function test_bookmarks_sync_endpoint(): void
    {
        $user = User::factory()->create();
        $h1 = $this->record('bukhari', '301');
        $h2 = $this->record('muslim', '302');

        $this->actingAs($user)
            ->postJson('/vipendwa/sync', ['ids' => [$h1->id, $h2->id]])
            ->assertOk()
            ->assertJson([
                'ids' => [$h1->id, $h2->id],
                'count' => 2,
            ]);

        $this->assertEquals(2, $user->bookmarks()->count());
    }
}
