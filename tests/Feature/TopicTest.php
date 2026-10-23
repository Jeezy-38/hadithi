<?php

namespace Tests\Feature;

use App\Livewire\TopicIndex;
use App\Models\Hadith;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class TopicTest extends TestCase
{
    use RefreshDatabase;

    public function test_topics_catalog_renders_successfully(): void
    {
        $this->seed();

        $response = $this->get(route('topics.index'));

        $response->assertOk()
            ->assertSee('Mada Kuu za Kiislamu')
            ->assertSee('Subira na Mitihani')
            ->assertSee('Tabia Njema na Maadili')
            ->assertSee('Tawhid na Misingi ya Imani')
            ->assertSee('Swala, Tohara na Ibada')
            ->assertSee('Tawba na Msamaha wa Allah')
            ->assertSee('Familia, Ndoa na Haki za Wazazi');
    }

    public function test_individual_topic_page_loads_and_filters_hadiths(): void
    {
        $this->seed();

        $response = $this->get(route('topics.show', 'subira'));

        $response->assertOk()
            ->assertSee('Subira na Mitihani')
            ->assertSee('Rudi kwenye Mada Zote');
    }

    public function test_livewire_can_select_and_clear_topic(): void
    {
        $this->seed();

        Livewire::test(TopicIndex::class)
            ->assertSee('Subira na Mitihani')
            ->call('selectTopic', 'tabia-njema')
            ->assertSet('topic', 'tabia-njema')
            ->assertSee('Tabia Njema na Maadili')
            ->call('clearTopic')
            ->assertSet('topic', '')
            ->assertSee('Mada Kuu za Kiislamu');
    }

    public function test_nav_drawer_and_desktop_header_link_to_topics(): void
    {
        $this->seed();

        $response = $this->get(route('library'));

        $response->assertOk()
            ->assertSee(route('topics.index'))
            ->assertSee('Mada za Kiislamu');
    }
}
