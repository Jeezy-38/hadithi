<?php

namespace Tests\Feature;

use App\Models\{Book, Chapter, Collection, Hadith};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class HadithAudioTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        Storage::fake('private');
        Process::fake(['*' => Process::result(output: 'ID3'.str_repeat('a', 256))]);
    }

    private function createTestHadith(bool $published = true): Hadith
    {
        $book = Book::firstOrCreate(
            ['collection_id' => Collection::where('slug', 'bukhari')->value('id'), 'number' => 1],
            ['title_sw' => 'Kitabu cha majaribio', 'title_ar' => 'كتاب الاختبار']
        );
        $chapter = Chapter::firstOrCreate(
            ['book_id' => $book->id, 'number' => 1],
            ['title_sw' => 'Mlango wa majaribio', 'title_ar' => 'باب الاختبار']
        );

        return Hadith::create([
            'chapter_id' => $chapter->id,
            'reference' => 'bukhari:test:audio',
            'number' => '1',
            'english' => 'Synthetic English audio test',
            'arabic' => 'نَصٌّ لِلاخْتِبَارِ',
            'swahili' => 'Rekodi ya majaribio ya sauti',
            'source_name' => 'Synthetic test fixture',
            'source_url' => 'https://example.com/arabic',
            'numbering_system' => 'Test only',
            'translator' => 'Test fixture',
            'translation_source_url' => 'https://example.com/swahili',
            'license' => 'Test only',
            'reviewed_by' => 'Test fixture',
            'reviewed_at' => '2026-01-01',
            'is_published' => $published,
        ]);
    }

    public function test_audio_endpoint_returns_404_for_invalid_hadith(): void
    {
        $response = $this->get('/audio/hadith/999999/sw');
        $response->assertNotFound();
    }

    public function test_audio_endpoint_returns_404_for_unpublished_hadith(): void
    {
        $hadith = $this->createTestHadith(false);
        $response = $this->get('/audio/hadith/'.$hadith->id.'/sw');
        $response->assertNotFound();
    }

    public function test_audio_endpoint_rejects_unsupported_languages(): void
    {
        $hadith = $this->createTestHadith();
        $response = $this->get('/audio/hadith/'.$hadith->id.'/fr');
        $response->assertStatus(400);
    }

    public function test_audio_endpoint_streams_audio_for_published_hadith(): void
    {
        $hadith = $this->createTestHadith();
        $response = $this->get('/audio/hadith/'.$hadith->id.'/sw');
        $response->assertOk();
        $response->assertHeader('Content-Type', 'audio/mpeg');
        $response->assertHeader('Accept-Ranges', 'bytes');
        Process::assertRan(fn ($process) => json_decode($process->input, true)['voice'] === 'sw-TZ-DaudiNeural');
    }

    public function test_audio_uses_male_voice_for_each_language(): void
    {
        $hadith = $this->createTestHadith();
        foreach (['ar' => 'ar-EG-ShakirNeural', 'en' => 'en-US-GuyNeural'] as $language => $voice) {
            $this->get('/audio/hadith/'.$hadith->id.'/'.$language)->assertOk();
            Process::assertRan(fn ($process) => json_decode($process->input, true)['voice'] === $voice);
        }
    }

    public function test_audio_is_reused_until_text_changes(): void
    {
        $hadith = $this->createTestHadith();
        $url = '/audio/hadith/'.$hadith->id.'/sw';
        $this->get($url)->assertOk();
        $this->get($url)->assertOk();
        Process::assertRanTimes(fn () => true, 1);
        $hadith->update(['swahili' => 'Maandishi mapya ya majaribio']);
        $this->get($url)->assertOk();
        Process::assertRanTimes(fn () => true, 2);
    }

    public function test_failed_or_non_audio_output_is_not_cached(): void
    {
        $hadith = $this->createTestHadith();
        foreach ([Process::result(output: 'ID3'.str_repeat('a', 256), exitCode: 1),
            Process::result(output: str_repeat('<html>Error</html>', 20))] as $result) {
            Process::fake(['*' => $result]);
            $this->get('/audio/hadith/'.$hadith->id.'/sw')->assertStatus(502);
            $this->assertSame([], Storage::disk('private')->allFiles());
        }
    }

    public function test_generated_audio_is_stored_on_private_disk(): void
    {
        $hadith = $this->createTestHadith();
        $this->get('/audio/hadith/'.$hadith->id.'/sw')->assertOk();
        $files = Storage::disk('private')->allFiles('audio_cache/male-v1');
        $this->assertCount(1, $files);
        $this->assertStringStartsWith('audio_cache/male-v1/hadith_'.$hadith->id.'_sw_', $files[0]);
    }

    public function test_legacy_audio_is_served_without_regenerating(): void
    {
        $hadith = $this->createTestHadith();
        Storage::disk('private')->put('audio_cache/hadith_'.$hadith->id.'_ar.mp3', 'ID3'.str_repeat('b', 300));
        $this->get('/audio/hadith/'.$hadith->id.'/ar')->assertOk()->assertHeader('Content-Length', '303');
        Process::assertNothingRan();
    }

    public function test_range_requests_return_partial_content_for_seeking(): void
    {
        $hadith = $this->createTestHadith();
        $url = '/audio/hadith/'.$hadith->id.'/sw';
        $size = strlen($this->get($url)->assertOk()->getContent());
        $last = $size - 1;

        $partial = $this->get($url, ['Range' => 'bytes=0-9']);
        $partial->assertStatus(206)
            ->assertHeader('Content-Range', "bytes 0-9/{$size}")
            ->assertHeader('Content-Length', '10');
        $this->assertSame('ID3aaaaaaa', $partial->getContent());

        $this->get($url, ['Range' => 'bytes=250-'])->assertStatus(206)->assertHeader('Content-Range', "bytes 250-{$last}/{$size}");
        $this->get($url, ['Range' => 'bytes=-4'])->assertStatus(206)->assertHeader('Content-Range', 'bytes '.($size - 4)."-{$last}/{$size}");
        $this->get($url, ['Range' => 'bytes=999-'])->assertStatus(416)->assertHeader('Content-Range', "bytes */{$size}");
    }

    public function test_warm_command_generates_only_missing_audio(): void
    {
        $hadith = $this->createTestHadith();
        Storage::disk('private')->put(app(\App\Services\HadithAudio::class)->key($hadith, 'sw'), 'ID3'.str_repeat('c', 300));

        $this->artisan('hadith:audio-warm', ['--id' => [$hadith->id]])
            ->expectsOutputToContain('Zipo tayari: 1 · Za kutengeneza: 2')
            ->assertSuccessful();
        Process::assertRanTimes(fn () => true, 2);
        $this->assertCount(3, Storage::disk('private')->allFiles('audio_cache/male-v1'));

        // Mara ya pili hakuna kazi.
        $this->artisan('hadith:audio-warm', ['--id' => [$hadith->id]])
            ->expectsOutputToContain('Za kutengeneza: 0')
            ->assertSuccessful();
        Process::assertRanTimes(fn () => true, 2);
    }

    public function test_warm_command_dry_run_and_lang_filter(): void
    {
        $hadith = $this->createTestHadith();
        $this->artisan('hadith:audio-warm', ['--id' => [$hadith->id], '--lang' => ['ar'], '--dry-run' => true])
            ->expectsOutputToContain('Za kutengeneza: 1')
            ->assertSuccessful();
        Process::assertNothingRan();

        $this->artisan('hadith:audio-warm', ['--lang' => ['fr']])->assertFailed();
    }

    public function test_warm_command_reports_failures(): void
    {
        $hadith = $this->createTestHadith();
        Process::fake(['*' => Process::result(output: 'not audio', exitCode: 1)]);
        $this->artisan('hadith:audio-warm', ['--id' => [$hadith->id], '--lang' => ['sw']])
            ->expectsOutputToContain('Zimeshindwa: 1')
            ->assertFailed();
        $this->assertSame([], Storage::disk('private')->allFiles());
    }
}
