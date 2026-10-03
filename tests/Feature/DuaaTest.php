<?php

namespace Tests\Feature;

use App\Livewire\DuaIndex;
use App\Models\Dua;
use App\Models\DuaCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class DuaaTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_duaa_index_renders_and_shows_categories_and_duas(): void
    {
        $this->get(route('duaa.index'))
            ->assertOk()
            ->assertSee('HISN AL-MUSLIM')
            ->assertSee('Adhkar za Asubuhi')
            ->assertSee('Sayyidul Istighfar')
            ->assertSee('Digital Tasbih');
    }

    public function test_duaa_category_filtering(): void
    {
        Livewire::test(DuaIndex::class)
            ->call('selectCategory', 'asubuhi')
            ->assertSet('category', 'asubuhi')
            ->assertSee('Sayyidul Istighfar')
            ->call('selectCategory', 'usingizi')
            ->assertSet('category', 'usingizi')
            ->assertSee('Dua ya Kulala')
            ->assertDontSee('Sayyidul Istighfar');
    }

    public function test_duaa_search_by_keyword(): void
    {
        Livewire::test(DuaIndex::class)
            ->set('search', 'Sayyidul')
            ->assertSee('Sayyidul Istighfar')
            ->assertDontSee('Dua ya Kulala');
    }

    public function test_duaa_detail_page_renders_with_arabic_and_counter(): void
    {
        $dua = Dua::published()->firstOrFail();

        $this->get(route('duaa.show', $dua))
            ->assertOk()
            ->assertSee($dua->title_sw)
            ->assertSee($dua->arabic)
            ->assertSee($dua->swahili)
            ->assertSee('dua-counter-widget')
            ->assertSee('dua-count-btn');
    }

    public function test_unpublished_duaa_returns_404(): void
    {
        $dua = Dua::published()->firstOrFail();
        $dua->update(['is_published' => false]);

        $this->get(route('duaa.show', $dua))
            ->assertNotFound();
    }

    public function test_tasbih_page_renders_successfully(): void
    {
        $this->get(route('tasbih'))
            ->assertOk()
            ->assertSee('DIGITAL')
            ->assertSee('Subhaanallaah')
            ->assertSee('tasbih-tap-btn')
            ->assertSee('ring-progress');
    }
}
