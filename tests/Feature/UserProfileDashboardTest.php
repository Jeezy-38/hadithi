<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Chapter;
use App\Models\Collection;
use App\Models\Hadith;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserProfileDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_profile_dashboard(): void
    {
        $response = $this->get(route('profile'));
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_sees_modern_dashboard_ui(): void
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
            'title_sw' => 'Mlango wa Mwanzo',
            'title_ar' => 'باب',
        ]);
        $hadith = Hadith::create([
            'chapter_id' => $chapter->id,
            'reference' => 'bukhari:1',
            'number' => '1',
            'arabic' => 'إِنَّمَا الأَعْمَالُ بِالنِّيَّاتِ',
            'swahili' => 'Hakika matendo yanategemea nia.',
            'english' => 'Actions are by intentions.',
            'source_name' => 'Test',
            'source_url' => 'https://example.com',
            'numbering_system' => 'Test',
            'translator' => 'Test',
            'translation_source_url' => 'https://example.com/trans',
            'license' => 'CC-BY',
            'reviewed_by' => 'Reviewer',
            'reviewed_at' => '2026-01-01',
            'is_published' => true,
        ]);

        $user = User::factory()->create([
            'name' => 'Hassan Ali',
            'email' => 'hassan@example.com',
        ]);

        $user->bookmarks()->attach($hadith->id);

        $response = $this->actingAs($user)->get(route('profile'));

        $response->assertOk()
            ->assertSee('Hassan Ali')
            ->assertSee('Dashibodi ya Msomaji')
            ->assertSee('Hadithi Nilizosoma')
            ->assertSee('Vipendwa (Bookmarks)')
            ->assertSee('Lengo la Mwezi')
            ->assertSee('Endelea Kusoma');
    }
}
