<?php

namespace App\Console\Commands;

use App\Models\Hadith;
use App\Services\HadithAudio;
use Illuminate\Console\Command;

class WarmHadithAudio extends Command
{
    protected $signature = 'hadith:audio-warm
        {--lang=* : Lugha za kuandaa (sw, ar, en). Chaguo-msingi: zote}
        {--id=* : Hadith maalum tu (ID)}
        {--limit=0 : Idadi ya juu ya MP3 mpya kutengeneza (0 = bila kikomo)}
        {--force : Tengeneza upya hata kama sauti ipo}
        {--dry-run : Onyesha tu kinachokosekana, bila kutengeneza}';

    protected $description = 'Andaa MP3 za sauti za hadith mapema ili watumiaji wasisubiri TTS';

    public function handle(HadithAudio $audio): int
    {
        $langs = $this->option('lang') ?: HadithAudio::LANGS;
        if ($bad = array_diff($langs, HadithAudio::LANGS)) {
            $this->error('Lugha zisizojulikana: '.implode(', ', $bad));

            return self::FAILURE;
        }

        $limit = max(0, (int) $this->option('limit'));
        $query = Hadith::published()->orderBy('id');
        if ($ids = $this->option('id')) {
            $query->whereIn('id', array_map('intval', $ids));
        }

        // Tafuta kwanza kinachokosekana ili progress bar iwe sahihi.
        $todo = [];
        $skipped = 0;
        foreach ($query->cursor() as $hadith) {
            foreach ($langs as $lang) {
                if ($audio->text($hadith, $lang) === '') {
                    continue;
                }
                if (! $this->option('force') && $audio->hasFresh($hadith, $lang)) {
                    $skipped++;
                    continue;
                }
                $todo[] = [$hadith, $lang];
            }
        }
        if ($limit > 0) {
            $todo = array_slice($todo, 0, $limit);
        }

        $this->info(sprintf('Zipo tayari: %d · Za kutengeneza: %d', $skipped, count($todo)));
        if ($todo === [] || $this->option('dry-run')) {
            foreach ($this->option('dry-run') ? $todo : [] as [$hadith, $lang]) {
                $this->line("  #{$hadith->id} {$hadith->reference} [{$lang}]");
            }

            return self::SUCCESS;
        }

        $failed = [];
        $bar = $this->output->createProgressBar(count($todo));
        $bar->start();
        foreach ($todo as [$hadith, $lang]) {
            try {
                $audio->generate($hadith, $lang, throwOnStoreFailure: true);
            } catch (\Throwable $exception) {
                $failed[] = "#{$hadith->id} [{$lang}]: ".$exception->getMessage();
            }
            $bar->advance();
        }
        $bar->finish();
        $this->newLine(2);

        $this->info(sprintf('Zimetengenezwa: %d', count($todo) - count($failed)));
        if ($failed) {
            $this->warn(sprintf('Zimeshindwa: %d (endesha tena command hii kujaribu hizo tu)', count($failed)));
            foreach (array_slice($failed, 0, 20) as $line) {
                $this->line('  '.$line);
            }

            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}
