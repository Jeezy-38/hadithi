<?php

namespace Tests\Feature;

use App\Livewire\Library;
use App\Models\{Book, Chapter, Collection, Hadith};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class LibraryTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    // Deliberately synthetic text, never religious content. Fixtures stay in the test database.
    private function record(string $collection = 'bukhari', string $number = '1', bool $published = true): Hadith
    {
        $book = Book::firstOrCreate(['collection_id' => Collection::where('slug', $collection)->value('id'), 'number' => 1], ['title_sw' => 'Kitabu cha majaribio '.$collection, 'title_ar' => 'كتاب الاختبار']);
        $chapter = Chapter::firstOrCreate(['book_id' => $book->id, 'number' => 1], ['title_sw' => 'Mlango wa majaribio', 'title_ar' => 'باب الاختبار']);
        return Hadith::create([
            'chapter_id' => $chapter->id, 'reference' => "$collection:test:$number", 'number' => $number,
            'english' => 'Synthetic English sample', 'arabic' => 'نَصٌّ لِلاخْتِبَارِ', 'swahili' => "Rekodi ya majaribio $collection $number",
            'source_name' => 'Synthetic test fixture', 'source_url' => 'https://example.com/arabic',
            'numbering_system' => 'Test only', 'translator' => 'Test fixture', 'translation_source_url' => 'https://example.com/swahili',
            'license' => 'Test only', 'reviewed_by' => 'Test fixture', 'reviewed_at' => '2026-01-01', 'is_published' => $published,
        ]);
    }

    public function test_empty_library_is_honest_and_both_collections_are_available(): void
    {
        $this->get('/')->assertOk()->assertSee('Sahih al-Bukhari')->assertSee('Sahih Muslim')->assertSee('Inasubiri maudhui yaliyohakikiwa');
    }

    public function test_search_matches_swahili_arabic_without_vowels_and_numbers(): void
    {
        $record = $this->record('bukhari', '42a');
        // Search-term matches are wrapped in <mark> in the rendered excerpt, so a term that
        // occurs inside $record->swahili (e.g. "42a", "MAJARIBIO") breaks a literal full-text
        // match; "Na. 42a" (the card's number line) is never highlighted and stays intact.
        foreach (['MAJARIBIO', 'نص للاختبار', '42a', 'Synthetic English'] as $term) {
            Livewire::test(Library::class)->set('search', $term)->assertSee('Na. 42a');
        }
        Livewire::test(Library::class)->set('search', '%')->assertDontSee($record->swahili)->assertSee('Hakuna hadith iliyopatikana.');
    }

    public function test_filters_and_parent_changes_do_not_mix_collections(): void
    {
        $bukhari = $this->record();
        $muslim = $this->record('muslim');
        Livewire::test(Library::class)->call('chooseCollection', 'bukhari')
            ->set('book', (string) $bukhari->chapter->book_id)->set('chapter', (string) $bukhari->chapter_id)
            ->assertSee($bukhari->swahili)->assertDontSee($muslim->swahili)
            ->call('chooseCollection', 'muslim')->assertSet('book', '')->assertSet('chapter', '')
            ->assertSee($muslim->swahili)->assertDontSee($bukhari->swahili)
            ->set('book', (string) $bukhari->chapter->book_id)->assertDontSee($bukhari->swahili)->assertDontSee($muslim->swahili)
            ->call('clearFilters')->assertSee($bukhari->swahili)->assertSee($muslim->swahili);
    }

    public function test_unpublished_hadith_are_hidden_in_catalog_and_direct_urls(): void
    {
        $record = $this->record('muslim', 'secret', false);
        Livewire::test(Library::class)->assertDontSee($record->swahili)->assertDontSee($record->chapter->book->title_sw);
        $this->get(route('hadith.show', $record))->assertNotFound();
    }

    public function test_reader_shows_both_languages_and_attribution(): void
    {
        $record = $this->record();
        $this->get(route('hadith.show', $record))->assertOk()->assertSee($record->arabic)->assertSee($record->swahili)->assertSee($record->reference)->assertSee('https://example.com/swahili')->assertSee('lang="ar"', false)->assertSee('Synthetic English sample')->assertSee('audio-play')->assertSee('reading-language');
    }

    public function test_pagination_resets_when_search_changes(): void
    {
        for ($i = 1; $i <= 11; $i++) {
            $this->record('bukhari', (string) $i);
        }
        // "Na. 11" (unhighlighted) rather than the full swahili text, which now has the
        // matched "bukhari 11" wrapped in <mark> by the search-highlighting feature.
        Livewire::test(Library::class)->call('nextPage')->assertSee('Ukurasa 2 / 2')
            ->set('search', 'bukhari 11')->assertSet('paginators.page', 1)->assertSee('Na. 11');
    }

    public function test_shared_url_restores_search_and_collection(): void
    {
        $bukhari = $this->record('bukhari', '42');
        $muslim = $this->record('muslim', '99');
        // "Na. 42" rather than the full swahili text: the search term "42" is now wrapped in
        // <mark> inside the rendered excerpt, so a literal full-text match would break.
        $this->get('/?collection=bukhari&q=42')->assertOk()->assertSee('Na. 42')->assertDontSee($muslim->swahili);
    }

    private function dataset(): array
    {
        return [
            'collection' => 'bukhari', 'reference' => 'bukhari:test:101', 'number' => '101',
            'book' => ['number' => 1, 'title_sw' => 'Kitabu cha majaribio', 'title_ar' => 'كتاب الاختبار'],
            'chapter' => ['number' => 1, 'title_sw' => 'Mlango wa majaribio', 'title_ar' => 'باب الاختبار'],
            'arabic' => 'نص للاختبار', 'swahili' => 'Rekodi ya majaribio pekee',
            'source_name' => 'Test', 'source_url' => 'https://example.com/arabic', 'numbering_system' => 'Test',
            'translator' => 'Test', 'translation_source_url' => 'https://example.com/swahili', 'license' => 'Test',
            'reviewed_by' => 'Test', 'reviewed_at' => '2026-01-01', 'is_published' => true,
        ];
    }

    private function import(array $rows, int $expected): void
    {
        $path = tempnam(sys_get_temp_dir(), 'hadith-test-');
        try {
            file_put_contents($path, json_encode($rows));
            $this->artisan('hadith:import', ['file' => $path])->assertExitCode($expected);
        } finally {
            unlink($path);
        }
    }

    public function test_import_is_repeatable_and_refreshes_search_fields(): void
    {
        $row = $this->dataset();
        $this->import([$row], 0);
        $row['swahili'] = 'Maandishi yaliyosasishwa';
        $this->import([$row], 0);
        $this->assertDatabaseCount('hadiths', 1);
        $this->assertDatabaseCount('books', 1);
        $this->assertDatabaseCount('chapters', 1);
        $this->assertSame(1, Hadith::search('YALIYOSASISHWA')->count());
    }

    public function test_invalid_source_or_missing_review_rejects_entire_import(): void
    {
        $valid = $this->dataset();
        $invalid = [...$valid, 'reference' => 'bukhari:test:102', 'source_url' => 'javascript:alert(1)'];
        $this->import([$valid, $invalid], 1);
        $this->assertDatabaseCount('hadiths', 0);
        $this->assertDatabaseCount('books', 0);
        $invalid = $valid;
        unset($invalid['reviewed_by']);
        $this->import([$invalid], 1);
        $this->assertDatabaseCount('hadiths', 0);
    }
}
