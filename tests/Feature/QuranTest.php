<?php

namespace Tests\Feature;

use App\Livewire\QuranIndex;
use App\Models\QuranSurah;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Livewire\Livewire;
use Tests\TestCase;

class QuranTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_quran_index_renders_and_shows_114_surahs(): void
    {
        $this->get(route('quran.index'))
            ->assertOk()
            ->assertSee('TUKUFU')
            ->assertSee('Al-Faatiha')
            ->assertSee('An-Naas')
            ->assertSee('114');
    }

    public function test_quran_surah_search_by_name(): void
    {
        Livewire::test(QuranIndex::class)
            ->set('search', 'Yaasiin')
            ->assertSee('Yaasiin')
            ->assertDontSee('Al-Baqara');
    }

    public function test_quran_surah_filter_by_revelation_type(): void
    {
        Livewire::test(QuranIndex::class)
            ->call('setType', 'Madani')
            ->assertSet('type', 'Madani')
            ->assertSee('Al-Baqara')
            ->assertDontSee("Al-An'aam");
    }

    public function test_quran_show_renders_surah_detail(): void
    {
        $this->get(route('quran.show', 1))
            ->assertOk()
            ->assertSee('Al-Faatiha')
            ->assertSee('الفَاتِحَة')
            ->assertSee('Ufunguzi')
            ->assertSee('quran-play-all-btn')
            ->assertSee('quran-ayah-card');
    }

    public function test_quran_index_has_modern_search_and_popular_chips(): void
    {
        $this->get(route('quran.index'))
            ->assertOk()
            ->assertSee('quran-search-input')
            ->assertSee('quran-quick-wrapper')
            ->assertSee('quran-quick-chip')
            ->assertSee('SURA MAARUFU');
    }

    public function test_quran_show_has_standalone_bismillah_and_ayah_1_is_separated(): void
    {
        // Surah 112 (Al-Ikhlas)
        $res112 = $this->get(route('quran.show', 112));
        $res112->assertOk()
            ->assertSee('quran-bismillah-box')
            ->assertSee('BISMILLAHIR RAHMAANIR RAHIIM')
            ->assertSee('قُلْ هُوَ ٱللَّهُ أَحَدٌ')
            ->assertDontSee('btn-font-decrease')
            ->assertDontSee('btn-font-increase')
            ->assertDontSee('btn-font-reset');

        // Check Ayah 1 does not contain Bismillah merged in its text
        $ayah112_1 = \App\Models\QuranAyah::where('surah_number', 112)->where('verse_number', 1)->first();
        $this->assertNotNull($ayah112_1);
        $this->assertSame('قُلْ هُوَ ٱللَّهُ أَحَدٌ', $ayah112_1->arabic_text);

        // Surah 1 (Al-Faatiha) - Bismillah is Verse 1 itself
        $res1 = $this->get(route('quran.show', 1));
        $res1->assertOk()
            ->assertDontSee('quran-bismillah-box'); // Standalone prelude box not shown on Surah 1
        
        $ayah1_1 = \App\Models\QuranAyah::where('surah_number', 1)->where('verse_number', 1)->first();
        $this->assertNotNull($ayah1_1);
        $this->assertStringContainsString('بِسْمِ', $ayah1_1->arabic_text);
    }

    public function test_invalid_surah_returns_404(): void
    {
        $this->get('/quran/0')->assertNotFound();
        $this->get('/quran/115')->assertNotFound();
        $this->get('/quran/abc')->assertNotFound();
    }

    public function test_quran_import_command_validation(): void
    {
        $exitCode = Artisan::call('quran:import', ['--surah' => 999]);
        $this->assertEquals(1, $exitCode);
    }
}
