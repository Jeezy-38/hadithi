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
