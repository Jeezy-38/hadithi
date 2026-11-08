<?php

namespace App\Http\Controllers;

use App\Models\Hadith;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class AiResearchController extends Controller
{
    public static function enabled(): bool
    {
        return filled(config('services.ai.key'));
    }

    public function index()
    {
        return view('ai.index', ['enabled' => self::enabled(), 'limit' => (int) config('services.ai.daily_limit')]);
    }

    public function ask(Request $request)
    {
        abort_unless(self::enabled(), 404);
        $question = Str::limit(trim((string) $request->input('q', '')), 300, '');
        if (mb_strlen($question) < 4) {
            return response()->json(['error' => 'short'], 422);
        }

        // Kikomo cha maswali kwa siku kwa kila mtumiaji (au IP kwa wageni).
        $who = $request->user()?->id ? 'u'.$request->user()->id : 'ip'.$request->ip();
        $key = 'ai:'.$who.':'.now('Africa/Dar_es_Salaam')->toDateString();
        $used = (int) Cache::get($key, 0);
        if ($used >= (int) config('services.ai.daily_limit')) {
            return response()->json(['error' => 'limit'], 429);
        }

        $hadiths = $this->retrieve($question);
        if ($hadiths->isEmpty()) {
            return response()->json(['answer' => null, 'sources' => [], 'none' => true]);
        }

        $context = $hadiths->values()->map(fn ($h, $i) => '['.($i + 1).'] '.$h->chapter->book->collection->name.' Na. '.$h->number.': '
            .Str::limit(trim(($h->swahili ?: $h->english) ?: ''), 900))->implode("\n\n");

        $system = "Wewe ni msaidizi wa utafiti wa Bayt Ul Hadith. Jibu swali la mtumiaji KWA KUTUMIA hadith zilizotolewa pekee, kwa lugha ya swali lake (Kiswahili au Kiingereza). "
            ."Taja marejeo kwa [1], [2]. Usiongeze hadith, maelezo ya wanazuoni au hukumu zisizo kwenye vyanzo. Usitoe fatwa; kwa masuala ya hukumu mshauri aulize mwanazuoni. "
            ."Ikiwa hadith hazitoshi kujibu, sema hivyo wazi. Jibu kwa ufupi (aya 2–4).";

        try {
            $answer = $this->generateAiAnswer($system, $context, $question);
        } catch (\Throwable $e) {
            report($e);

            return response()->json(['error' => 'fail'], 502);
        }

        Cache::put($key, $used + 1, now()->addDay());

        return response()->json([
            'answer' => $answer,
            'remaining' => max(0, (int) config('services.ai.daily_limit') - $used - 1),
            'sources' => $hadiths->values()->map(fn ($h, $i) => [
                'n' => $i + 1,
                'ref' => $h->chapter->book->collection->name.' · Na. '.$h->number,
                'url' => route('hadith.show', $h),
            ]),
        ]);
    }

    /**
     * Tuma ombi kwa Google Gemini (Bure) au Anthropic Claude.
     */
    private function generateAiAnswer(string $system, string $context, string $question): string
    {
        $key = (string) config('services.ai.key');
        $provider = config('services.ai.provider') ?: (str_starts_with($key, 'AIza') ? 'gemini' : 'anthropic');
        $model = config('services.ai.model') ?: ($provider === 'gemini' ? 'gemini-1.5-flash' : 'claude-haiku-5-5');
        $maxTokens = (int) config('services.ai.max_tokens', 800);

        if ($provider === 'gemini') {
            $endpoint = 'https://generativelanguage.googleapis.com/v1beta/models/'.$model.':generateContent?key='.$key;
            $res = Http::timeout(30)->post($endpoint, [
                'systemInstruction' => [
                    'parts' => [['text' => $system]],
                ],
                'contents' => [
                    [
                        'role' => 'user',
                        'parts' => [
                            ['text' => "Hadithi zilizotolewa kutoka Bayt Ul Hadith:\n\n{$context}\n\nSwali la mtumiaji: {$question}"],
                        ],
                    ],
                ],
                'generationConfig' => [
                    'maxOutputTokens' => $maxTokens,
                    'temperature' => 0.2,
                ],
            ]);

            if (! $res->ok()) {
                throw new \RuntimeException('Gemini API '.$res->status().': '.Str::limit($res->body(), 300));
            }

            $answer = $res->json('candidates.0.content.parts.0.text');
            if (empty($answer)) {
                throw new \RuntimeException('Gemini API returned empty text');
            }

            return trim($answer);
        }

        // Anthropic Claude
        $res = Http::withHeaders(['x-api-key' => $key, 'anthropic-version' => '2023-06-01'])
            ->timeout(30)->post('https://api.anthropic.com/v1/messages', [
                'model' => $model,
                'max_tokens' => $maxTokens,
                'system' => $system,
                'messages' => [['role' => 'user', 'content' => "Hadith:\n\n{$context}\n\nSwali: {$question}"]],
            ]);

        if (! $res->ok()) {
            throw new \RuntimeException('AI API '.$res->status().': '.Str::limit($res->body(), 300));
        }

        return trim(collect($res->json('content', []))->where('type', 'text')->pluck('text')->implode("\n"));
    }

    /** Hadith zenye maneno mengi ya swali (si utafutaji wa kisemantiki). */
    private function retrieve(string $question)
    {
        preg_match_all('/\p{L}{4,}/u', mb_strtolower($question), $m);
        $stop = ['nini', 'kuhusu', 'hukumu', 'what', 'about', 'does', 'islam', 'kiislamu', 'unasemaje', 'says', 'mtume', 'amesema', 'hadith', 'ipi', 'vipi'];
        $words = array_slice(array_values(array_diff(array_unique($m[0]), $stop)), 0, 6);
        if (! $words) {
            return collect();
        }
        $q = Hadith::published()->with('chapter.book.collection')->where(function ($qq) use ($words) {
            foreach ($words as $w) {
                $like = '%'.str_replace(['!', '%', '_'], ['!!', '!%', '!_'], $w).'%';
                $qq->orWhereRaw("LOWER(search_swahili) LIKE ? ESCAPE '!'", [$like])->orWhereRaw("LOWER(english) LIKE ? ESCAPE '!'", [$like]);
            }
        })->limit(60)->get();

        return $q->map(function ($h) use ($words) {
            $text = mb_strtolower($h->search_swahili.' '.$h->english);
            $h->ai_score = collect($words)->filter(fn ($w) => str_contains($text, $w))->count();

            return $h;
        })->sortByDesc('ai_score')->take(6);
    }
}
