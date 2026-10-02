<?php
namespace Tests\Feature;

use App\Models\Hadith;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StarterDatasetTest extends TestCase
{
    use RefreshDatabase;

    public function test_starter_dataset_preserves_original_text_and_is_repeatable(): void
    {
        $this->seed();
        $file = database_path('data/hadith-starter.json');
        // Counts are derived from the dataset file itself (not hardcoded) since it keeps
        // growing as new reviewed hadith are added.
        $rows = json_decode(file_get_contents($file), true, 512, JSON_THROW_ON_ERROR);
        $this->artisan('hadith:import', ['file' => $file])->assertSuccessful();
        $this->artisan('hadith:import', ['file' => $file])->assertSuccessful();
        $this->assertDatabaseCount('hadiths', count($rows));
        $this->assertDatabaseCount('books', collect($rows)->unique(fn ($r) => $r['collection'].'|'.$r['book']['number'])->count());
        $this->assertDatabaseCount('chapters', collect($rows)->unique(fn ($r) => $r['collection'].'|'.$r['book']['number'].'|'.$r['chapter']['number'])->count());

        foreach (Hadith::all() as $hadith) {
            // Only hadith with an individually archived raw snapshot (source_sha256 set) are
            // re-checked byte-for-byte here. Provisional batch entries (source_sha256 null)
            // are covered instead by the batch-level SHA-256 under a "discovery-batch-*" key
            // in manifest.json, and get real numbering/book/chapter once cross-referenced.
            if ($hadith->source_sha256) {
                $path = database_path('data/sources/hadeethenc-sw-'.$hadith->source_record_id.'.json');
                $source = json_decode(file_get_contents($path), true, 512, JSON_THROW_ON_ERROR);
                $this->assertSame($source['hadeeth_ar'], $hadith->arabic);
                $this->assertSame($source['hadeeth'], $hadith->swahili);
                $englishPath = database_path('data/sources/hadeethenc-en-'.$hadith->source_record_id.'.json');
                $english = json_decode(file_get_contents($englishPath), true, 512, JSON_THROW_ON_ERROR);
                $manifest = json_decode(file_get_contents(database_path('data/sources/manifest.json')), true);
                $this->assertSame($english['hadeeth'], $hadith->english);
                $this->assertSame($manifest['en-'.$hadith->source_record_id]['sha256'], hash_file('sha256', $englishPath));
                $this->assertSame(hash_file('sha256', $path), $hadith->source_sha256);
            }
            if (! $hadith->is_published) {
                // Zilizofichwa (mf. chanzo au daraja lenye shaka) hazionekani kwa umma.
                $this->get(route('hadith.show', $hadith))->assertNotFound();
                continue;
            }
            $page = $this->get(route('hadith.show', $hadith))->assertOk()->assertSee('Masharti')->assertSee($hadith->swahili);
            if ($hadith->reference_url) {
                $page->assertSee($hadith->reference_url);
            }
        }
    }
}
