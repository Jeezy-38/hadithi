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

        return 'audio_cache/male-v2/hadith_'.$hadith->id.'_'.$lang.'_'
            .hash('sha256', $voice.'|'.$this->rawText($hadith, $lang)).'.mp3';
    }

    public function legacyKey(Hadith $hadith, string $lang): string
    {
        return 'audio_cache/hadith_'.$hadith->id.'_'.$lang.'.mp3';
    }

    public function studioKey(Hadith $hadith): string
    {
        return 'audio_cache/studio/hadith_'.$hadith->id.'.mp3';
    }

    /**
     * Njia ya 3: Pata sauti halisi ya studio (Sheikh) ikipatikana.
     */
    public function resolveStudioAudio(Hadith $hadith): ?string
    {
        $studioUrl = $hadith->getStudioAudioUrl();
        if (! $studioUrl) {
            return null;
        }

        $key = $this->studioKey($hadith);
        if (Storage::disk('private')->exists($key)) {
            return (string) Storage::disk('private')->get($key);
        }

        if ($this->disk() !== Storage::disk('private') && $this->disk()->exists($key)) {
            $content = (string) $this->disk()->get($key);
            try {
                Storage::disk('private')->put($key, $content);
            } catch (\Throwable) {
                // ignore local cache write failure
            }

            return $content;
        }

        // Ikiwa haipo kwenye akiba, jaribu kuipakua kutoka chanzo rasmi cha studio
        try {
            $response = \Illuminate\Support\Facades\Http::timeout(5)->get($studioUrl);
            if ($response->successful()) {
                $body = $response->body();
                $isMp3 = str_starts_with($body, 'ID3')
                    || (strlen($body) >= 2 && ord($body[0]) === 0xFF && (ord($body[1]) & 0xE0) === 0xE0);

                if ($isMp3 && strlen($body) > 200) {
                    try {
                        $this->disk()->put($key, $body, ['ContentType' => 'audio/mpeg']);
                        Storage::disk('private')->put($key, $body);
                    } catch (\Throwable) {
                        // ignore secondary write failure
                    }

                    return $body;
                }
            }
        } catch (\Throwable $e) {
            report($e);
        }

        return null;
    }

    /**
     * Mfumo Mchanganyiko: Njia ya 3 (Studio) ndani ya Njia ya 1 (Neural TTS).
     */
    public function getAudio(Hadith $hadith, string $lang): array
    {
        // 1. Kwa Kiarabu, kagua kwanza sauti halisi ya studio (Njia ya 3)
        if ($lang === 'ar') {
            $studioMp3 = $this->resolveStudioAudio($hadith);
            if ($studioMp3 !== null) {
                return [
                    'mp3' => $studioMp3,
                    'source' => 'studio',
                    'reciter' => 'Studio Recitation (Sheikh)',
                ];
            }
        }

        // 2. Njia ya 1 (Neural TTS & Akiba) kama msingi na mbadala salama
        $mp3 = $this->cached($hadith, $lang);
        if ($mp3 === null) {
            $mp3 = $this->generate($hadith, $lang);
        }

        $reciterName = match ($lang) {
            'sw' => 'Daudi (Kiswahili Neural)',
            'ar' => 'Hamed (Kiarabu Neural)',
            default => 'Guy (Kiingereza Neural)',
        };

        return [
            'mp3' => $mp3,
            'source' => 'neural',
            'reciter' => $reciterName,
        ];
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
        $voice = config('speech.voices.'.$lang) ?? 'ar-SA-HamedNeural';
        $mp3 = $this->synthesize($this->cleanText($this->text($hadith, $lang), $lang), $voice, $lang);
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
            default => $hadith->arabic,
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

    private function synthesize(string $text, string $voice, string $lang = 'ar'): string
    {
        // 1. Sauti Halisi ya Kiume ya Kiarabu (Sheikh) kupitia Gemini TTS
        if ($lang === 'ar' || str_starts_with($lang, 'ar')) {
            $geminiAudio = $this->synthesizeGeminiMale($text);
            if ($geminiAudio !== null && strlen($geminiAudio) > 500) {
                return $geminiAudio;
            }
        }

        $python = config('speech.python');
        if (! empty($python) && (file_exists($python) || is_executable($python))) {
            try {
                $result = Process::input(json_encode(['text' => $text, 'voice' => $voice], JSON_THROW_ON_ERROR))
                    ->timeout(120)
                    ->run([$python, base_path('scripts/synthesize-speech.py')]);

                if ($result->successful()) {
                    $mp3 = $result->output();
                    $isAudio = str_starts_with($mp3, 'ID3')
                        || str_starts_with($mp3, 'RIFF')
                        || (strlen($mp3) >= 2 && ord($mp3[0]) === 0xFF && (ord($mp3[1]) & 0xE0) === 0xE0);

                    if (strlen($mp3) > 100 && $isAudio) {
                        return $mp3;
                    }
                }
            } catch (\Throwable $e) {
                // fall through to HTTP synthesis
            }
        }

        // Pure PHP Fallback: Inafanya kazi kwenye seva yoyote na simu bila kuhitaji Python
        try {
            return $this->synthesizeHttp($text, $lang);
        } catch (\Throwable $e) {
            report($e);
            throw new RuntimeException('TTS imeshindwa: '.$e->getMessage(), 0, $e);
        }
    }

    /**
     * Sauti ya kiume ya kisomo cha hadithi (Sheikh) kwa kutumia Gemini TTS API.
     */
    private function synthesizeGeminiMale(string $text): ?string
    {
        $key = (string) config('services.ai.key');
        if (blank($key)) {
            return null;
        }

        $endpoint = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash-preview-tts:generateContent?key='.$key;
        $prompt = 'اقرأ بصوت قارئ رجل خاشع وواضح: '.trim($text);

        $payload = [
            'contents' => [
                ['parts' => [['text' => $prompt]]],
            ],
            'generationConfig' => [
                'responseModalities' => ['AUDIO'],
                'speechConfig' => [
                    'voiceConfig' => [
                        'prebuiltVoiceConfig' => [
                            'voiceName' => 'Puck', // Sauti ya kiume ya utulivu na heshima
                        ],
                    ],
                ],
            ],
        ];

        try {
            $res = \Illuminate\Support\Facades\Http::timeout(30)->post($endpoint, $payload);
            if ($res->ok()) {
                $b64 = $res->json('candidates.0.content.parts.0.inlineData.data');
                if (! empty($b64)) {
                    $pcm = base64_decode($b64);
                    if (strlen($pcm) > 100) {
                        return $this->pcmToWav($pcm, 24000, 1, 16);
                    }
                }
            }
        } catch (\Throwable $e) {
            report($e);
        }

        return null;
    }

    /**
     * Badilisha raw PCM bytes kuwa faili sahihi la WAV lenye header ya kiwango cha kimataifa.
     */
    private function pcmToWav(string $pcm, int $sampleRate = 24000, int $channels = 1, int $bitsPerSample = 16): string
    {
        $dataLen = strlen($pcm);
        $byteRate = $sampleRate * $channels * ($bitsPerSample / 8);
        $blockAlign = $channels * ($bitsPerSample / 8);

        $header = 'RIFF'.pack('V', 36 + $dataLen).'WAVEfmt '
            .pack('V', 16).pack('v', 1).pack('v', $channels)
            .pack('V', $sampleRate).pack('V', $byteRate)
            .pack('v', $blockAlign).pack('v', $bitsPerSample)
            .'data'.pack('V', $dataLen);

        return $header.$pcm;
    }

    /**
     * Fallback ya moja kwa moja ya HTTP (Pure PHP) isiyotegemea Python au maktaba za ndani.
     * Inafanya kazi kwenye seva yoyote ya mtandaoni (Laravel Cloud, VPS, n.k.) na simu zote.
     */
    public function synthesizeHttp(string $text, string $lang = 'ar'): string
    {
        $targetLang = ($lang === 'ar' || str_starts_with($lang, 'ar')) ? 'ar' : 'sw';

        $words = preg_split('/\s+/u', trim($text));
        $chunks = [];
        $current = '';

        foreach ($words as $word) {
            if (mb_strlen($current.' '.$word) > 180) {
                if ($current !== '') {
                    $chunks[] = trim($current);
                }
                $current = $word;
            } else {
                $current .= ($current === '' ? '' : ' ').$word;
            }
        }
        if ($current !== '') {
            $chunks[] = trim($current);
        }

        if (empty($chunks)) {
            throw new RuntimeException('Maandishi ya sauti hayapo.');
        }

        $combinedMp3 = '';
        foreach ($chunks as $chunk) {
            $url = 'https://translate.google.com/translate_tts?ie=UTF-8&q='.urlencode($chunk).'&tl='.$targetLang.'&client=tw-ob';
            $response = \Illuminate\Support\Facades\Http::timeout(12)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                    'Referer' => 'https://translate.google.com/',
                ])
                ->get($url);

            if (! $response->successful() || strlen($response->body()) < 100) {
                throw new RuntimeException('Imeshindikana kupata sauti ya Kiarabu kupitia HTTP.');
            }

            $combinedMp3 .= $response->body();
        }

        return $combinedMp3;
    }
}
