<?php

namespace App\Services;

use App\Models\Hadith;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

/**
 * Sauti ya hadith (TTS ya kiume kupitia edge-tts), inayotumiwa na route na `hadith:audio-warm`.
 */
class HadithAudio
{
    public const LANGS = ['sw', 'ar', 'en'];

    public function text(Hadith $hadith, string $lang): string
    {
        return trim((string) match ($lang) {
            'ar' => $hadith->arabic,
            'sw' => $hadith->swahili,
            'en' => $hadith->english,
        });
    }

    public function key(Hadith $hadith, string $lang): string
    {
        $voice = config('speech.voices.'.$lang);

        return 'audio_cache/male-v1/hadith_'.$hadith->id.'_'.$lang.'_'
            .hash('sha256', $voice.'|'.$this->rawText($hadith, $lang)).'.mp3';
    }

    public function legacyKey(Hadith $hadith, string $lang): string
    {
        return 'audio_cache/hadith_'.$hadith->id.'_'.$lang.'.mp3';
    }

    public function disk(): Filesystem
    {
        return Storage::disk(config('speech.disk'));
    }

    /** Sauti iliyohifadhiwa (mpya kwanza, kisha ya zamani), au null. */
    public function cached(Hadith $hadith, string $lang): ?string
    {
        $primaryDisk = $this->disk();
        $localDisk = Storage::disk('private');

        // 1. Kagua storage ya ndani kwanza kwa majibu ya papo hapo (0ms)
        foreach ([$this->key($hadith, $lang), $this->legacyKey($hadith, $lang)] as $key) {
            if ($localDisk->exists($key)) {
                return (string) $localDisk->get($key);
            }
        }

        // 2. Ikiwa primary disk ni ya nje (k.m. Cloudflare R2 / S3) na haikupatikana ndani, kagua huko
        if ($primaryDisk !== $localDisk) {
            foreach ([$this->key($hadith, $lang), $this->legacyKey($hadith, $lang)] as $key) {
                if ($primaryDisk->exists($key)) {
                    $content = (string) $primaryDisk->get($key);
                    try {
                        $localDisk->put($key, $content);
                    } catch (\Throwable) {
                        // Kupuuza kushindwa kwa uandishi wa akiba ya ndani
                    }

                    return $content;
                }
            }
        }

        return null;
    }

    public function hasFresh(Hadith $hadith, string $lang): bool
    {
        $key = $this->key($hadith, $lang);
        if (Storage::disk('private')->exists($key)) {
            return true;
        }

        return $this->disk()->exists($key);
    }

    /**
     * Tengeneza MP3 na uihifadhi. Kuhifadhi kukishindwa, MP3 bado inarudishwa.
     *
     * @throws RuntimeException TTS ikishindwa au ikirudisha kitu kisicho MP3.
     */
    public function generate(Hadith $hadith, string $lang, bool $throwOnStoreFailure = false): string
    {
        $mp3 = $this->synthesize($this->cleanText($this->text($hadith, $lang), $lang), config('speech.voices.'.$lang));
        $key = $this->key($hadith, $lang);

        try {
            $this->disk()->put($key, $mp3, ['ContentType' => 'audio/mpeg']);
            if ($this->disk() !== Storage::disk('private')) {
                try {
                    Storage::disk('private')->put($key, $mp3);
                } catch (\Throwable) {
                    // ignore secondary cache failure
                }
            }
        } catch (\Throwable $exception) {
            report($exception);
            if ($throwOnStoreFailure) {
                throw new RuntimeException('Imeshindikana kuhifadhi sauti: '.$exception->getMessage(), 0, $exception);
            }
        }

        return $mp3;
    }

    private function rawText(Hadith $hadith, string $lang): string
    {
        return (string) match ($lang) {
            'ar' => $hadith->arabic,
            'sw' => $hadith->swahili,
            'en' => $hadith->english,
        };
    }

    private function cleanText(string $text, string $lang): string
    {
        $clean = preg_replace('/\s+/u', ' ', preg_replace('/[«»"“”\r]/u', ' ', $text));

        if ($lang === 'sw') {
            $clean = preg_replace(
                ['/\b(?:s\.a\.w\.?|saw)\b/ui', '/\b(?:r\.a\.a\.?|raa)\b/ui', '/\b(?:r\.a\.?|ra)\b/ui', '/\b(?:s\.w\.t\.?|swt)\b/ui', '/\b(?:a\.s\.?|as)\b/ui'],
                ['Swallallahu alayhi wa sallam', 'Radhiyallahu anhuma', 'Radhiyallahu anhu', 'Subhanahu wa Ta\'ala', 'Alayhis Salam'],
                $clean
            );
        } elseif ($lang === 'ar') {
            $clean = str_replace(['ﷺ', 'ﷻ'], [' صَلَّى اللَّهُ عَلَيْهِ وَسَلَّمَ ', ' جَلَّ جَلَالُهُ '], $clean);
        }

        return trim($clean);
    }

    private function synthesize(string $text, string $voice): string
    {
        $result = Process::input(json_encode(['text' => $text, 'voice' => $voice], JSON_THROW_ON_ERROR))
            ->timeout(90)
            ->run([config('speech.python'), base_path('scripts/synthesize-speech.py')]);

        $mp3 = $result->output();
        $isMp3 = str_starts_with($mp3, 'ID3')
            || (strlen($mp3) >= 2 && ord($mp3[0]) === 0xFF && (ord($mp3[1]) & 0xE0) === 0xE0);

        if (! $result->successful() || strlen($mp3) <= 100 || ! $isMp3) {
            throw new RuntimeException('TTS imeshindwa: '.trim(substr($result->errorOutput(), 0, 300)));
        }

        return $mp3;
    }
}
