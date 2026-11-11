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
        if (! self::enabled()) {
            return response()->json([
                'error' => 'not_configured',
                'message' => 'Msaidizi wa AI unahitaji GEMINI_API_KEY kuwekwa kwenye faili la .env la seva ya baytulhadith.co.tz.',
            ], 200);
        }
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
     * Msaidizi wa AI wa mazungumzo (chat) kwa ajili ya hadithi maalum.
     */
    public function chat(Hadith $hadith, Request $request)
    {
        if (! self::enabled()) {
            return response()->json([
                'error' => 'not_configured',
                'message' => 'Msaidizi wa AI unahitaji GEMINI_API_KEY kuwekwa kwenye faili la .env la seva ya baytulhadith.co.tz.',
            ], 200);
        }

        $question = Str::limit(trim((string) $request->input('message', '')), 500, '');
        if (mb_strlen($question) < 2) {
            return response()->json(['error' => 'Swali ni fupi mno.'], 422);
        }

        $lang = in_array($request->input('lang'), ['en', 'sw', 'ar', 'es']) ? $request->input('lang') : 'sw';
        $cacheKey = "hadith:chat:{$hadith->id}:" . md5(mb_strtolower($question) . ':' . $lang);

        $answer = Cache::remember($cacheKey, now()->addDays(7), function () use ($hadith, $question, $lang) {
            return $this->generateHadithChatAnswer($hadith, $question, $lang);
        });

        if (empty($answer)) {
            return response()->json(['error' => 'Haikuwezekana kupata jibu kwa sasa. Jaribu tena.'], 502);
        }

        return response()->json([
            'success' => true,
            'answer' => $answer,
            'question' => $question,
            'lang' => $lang,
        ]);
    }

    /**
     * Tengeneza ufafanuzi wa kina au jibu la swali la msomaji kuhusu hadithi husika.
     */
    private function generateHadithChatAnswer(Hadith $hadith, string $question, string $lang): ?string
    {
        $key = (string) config('services.ai.key');
        if (blank($key)) {
            return null;
        }

        $colName = $hadith->chapter?->book?->collection?->name ?? 'Hadithi';
        $arabic = trim((string) $hadith->arabic);
        $translation = $lang === 'en' 
            ? trim((string) ($hadith->english ?: $hadith->swahili)) 
            : trim((string) ($hadith->swahili ?: $hadith->english));

        if ($lang === 'en') {
            $system = "You are a dignified, knowledgeable, gentle Islamic scholarly research assistant at Bayt Ul Hadith (baytulhadith.co.tz). "
                . "A user is reading Hadith {$colName} No. {$hadith->number} and has asked for clarification or deeper understanding. "
                . "Answer strictly based on authentic Islamic scholarship, explaining the context (Asbab al-Wurud if applicable), meaning of terms, and practical moral lessons with wisdom and respect. "
                . "Do not issue personal fatwas. If the question involves complex legal rulings, gently advise consulting verified scholars. Keep your response concise, structured (2-3 paragraphs with bullet points if helpful), and warm.";
            $prompt = "Hadith Reference: {$colName} No. {$hadith->number}\nArabic Text: {$arabic}\nEnglish Translation: {$translation}\n\nReader's Question: {$question}";
        } elseif ($lang === 'ar') {
            $system = "أنت مساعد علمي وباحث إسلامي جليل وموثوق في منصة بيت الحديث (baytulhadith.co.tz). "
                . "القارئ يقرأ حديث {$colName} رقم {$hadith->number} ويطلب توضيحاً أو شرحاً لمعنى أو عبرة في هذا الحديث. "
                . "أجب بالعربية الفصحى الرصينة والواضحة، موضحاً المفردات، سبب الورود إن وجد، والفوائد الإيمانية والعملية بأسلوب لطيف ومبسط. تجنب إصدار الفتاوى الخاصة، واجعل الإجابة مرتبة وموجزة (فقرتان إلى 3 فقرات).";
            $prompt = "الحديث: {$colName} رقم {$hadith->number}\nنص الحديث: {$arabic}\n\nسؤال القارئ: {$question}";
        } elseif ($lang === 'es') {
            $system = "Eres un asistente de estudio e investigación islámica en Bayt Ul Hadith (baytulhadith.co.tz). "
                . "El lector está estudiando el hadiz {$colName} N.º {$hadith->number} y necesita una aclaración o explicación detallada. "
                . "Responde en español claro y accesible basado en fuentes fidedignas, explicando el contexto, términos y lecciones prácticas con respeto y sabiduría (2-3 párrafos ordenados).";
            $prompt = "Hadiz: {$colName} N.º {$hadith->number}\nTexto en Árabe: {$arabic}\nTraducción: {$translation}\n\nPregunta del Lector: {$question}";
        } else {
            $system = "Wewe ni Msaidizi wa Kielimu wa Kiislamu mwenye hekima na utulivu katika maktaba ya Bayt Ul Hadith (baytulhadith.co.tz). "
                . "Msomaji anasoma Hadithi ya {$colName} Na. {$hadith->number} na ameomba ufafanuzi au maelezo zaidi kwa kuwa anahitaji kuelewa vizuri mafunzo na maana ya hadithi hii. "
                . "Jibu kwa hekima ya Kiislamu, lugha fasaha na nyepesi kueleweka ya Kiswahili, ukifafanua maana ya maneno, mukhtadha (asbabu al-wurud kama ipo), na mafunzo ya vitendo maishani. "
                . "Usitoe fatwa za kibinafsi; kwa masuala magumu ya kisheria mshauri kwa upole kuwasiliana na wanazuoni. Fanya jibu liwe la kuridhisha na nadhifu (aya 2-3 zenye mpangilio).";
            $prompt = "Hadithi: {$colName} Na. {$hadith->number}\nMatini ya Kiarabu: {$arabic}\nTafsiri ya Kiswahili: {$translation}\n\nSwali la Msomaji: {$question}";
        }

        $model = config('services.ai.model') ?: 'gemini-flash-lite-latest';
        $endpoint = 'https://generativelanguage.googleapis.com/v1beta/models/'.$model.':generateContent?key='.$key;
        $payload = [
            'systemInstruction' => [
                'parts' => [['text' => $system]],
            ],
            'contents' => [
                ['role' => 'user', 'parts' => [['text' => $prompt]]],
            ],
            'generationConfig' => [
                'maxOutputTokens' => 1000,
                'temperature' => 0.35,
            ],
        ];

        try {
            $res = Http::timeout(25)->post($endpoint, $payload);
            if (! $res->ok() && $model !== 'gemini-flash-lite-latest') {
                $fallback = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-lite-latest:generateContent?key='.$key;
                $res = Http::timeout(25)->post($fallback, $payload);
            }

            if ($res->ok()) {
                $text = $res->json('candidates.0.content.parts.0.text');
                if (filled($text)) {
                    return trim($text);
                }
            }
        } catch (\Throwable $e) {
            report($e);
        }

        return null;
    }

    /**
     * Pata mafundisho na faida za hadithi (Fawaid al-Hadith) kwa kutumia Gemini.
     */
    public function lessons(Hadith $hadith, Request $request)
    {
        if (! self::enabled()) {
            return response()->json([
                'error' => 'not_configured',
                'message' => 'Msaidizi wa AI unahitaji GEMINI_API_KEY kuwekwa kwenye faili la .env la seva ya baytulhadith.co.tz.',
            ], 200);
        }

        $lang = in_array($request->input('lang'), ['en', 'sw', 'ar', 'es']) ? $request->input('lang') : 'sw';
        $cacheKey = "hadith:lessons:{$hadith->id}:{$lang}";

        $lessons = Cache::rememberForever($cacheKey, function () use ($hadith, $lang) {
            return $this->generateHadithLessons($hadith, $lang);
        });

        if (empty($lessons)) {
            return response()->json(['error' => 'fail', 'message' => 'Haikuwezekana kupata mafundisho kwa sasa.'], 502);
        }

        return response()->json([
            'success' => true,
            'lessons' => $lessons,
            'lang' => $lang,
        ]);
    }

    /**
     * Tengeneza mafundisho 3-4 ya hadithi kwa lugha ya Kiswahili au Kiingereza kwa Gemini.
     */
    private function generateHadithLessons(Hadith $hadith, string $lang): array
    {
        $key = (string) config('services.ai.key');
        if (blank($key)) {
            return [];
        }

        $colName = $hadith->chapter?->book?->collection?->name ?? 'Hadithi';
        $arabic = trim((string) $hadith->arabic);
        $translation = $lang === 'en' 
            ? trim((string) ($hadith->english ?: $hadith->swahili)) 
            : trim((string) ($hadith->swahili ?: $hadith->english));

        if ($lang === 'en') {
            $system = "You are an Islamic scholar assistant at Bayt Ul Hadith. Provide 3-4 concise, dignified, practical lessons and moral benefits (Fawaid al-Hadith) derived strictly from this Hadith. Return ONLY a valid JSON array of strings: [\"lesson 1\", \"lesson 2\", \"lesson 3\"]. No markdown formatting outside JSON.";
            $prompt = "Hadith Reference: {$colName} No. {$hadith->number}\nArabic: {$arabic}\nEnglish Translation: {$translation}";
        } elseif ($lang === 'ar') {
            $system = "أنت باحث ومساعد علمي في بيت الحديث. استخرج 3 إلى 4 فوائد تربوية وإيمانية وعملية موجزة ومحكمة من هذا الحديث الشريف. أرجع فقط مصفوفة JSON صالحة من النصوص: [\"فائدة 1\", \"فائدة 2\", \"فائدة 3\"]. لا تضع أي نص خارج JSON.";
            $prompt = "الحديث: {$colName} رقم {$hadith->number}\nنص الحديث: {$arabic}";
        } elseif ($lang === 'es') {
            $system = "Eres un asistente de estudio islámico en Bayt Ul Hadith. Proporciona de 3 a 4 lecciones prácticas y beneficios morales concisos derivados estrictamente de este hadiz. Devuelve ÚNICAMENTE un array JSON válido de strings: [\"lección 1\", \"lección 2\", \"lección 3\"].";
            $prompt = "Hadiz: {$colName} N.º {$hadith->number}\nÁrabe: {$arabic}\nTraducción: {$translation}";
        } else {
            $system = "Wewe ni msaidizi wa kielimu wa Bayt Ul Hadith. Toa mafundisho na faida 3-4 fupi, zenye hekima na mafunzo ya kimaadili (Fawaid za Hadithi) zinazotokana moja kwa moja na hadithi hii. Rudisha JSON array ya strings pekee: [\"faida 1\", \"faida 2\", \"faida 3\"]. Usiweke maelezo mengine nje ya JSON.";
            $prompt = "Hadithi: {$colName} Na. {$hadith->number}\nMatini ya Kiarabu: {$arabic}\nTafsiri ya Kiswahili: {$translation}";
        }

        $model = config('services.ai.model') ?: 'gemini-flash-lite-latest';
        $endpoint = 'https://generativelanguage.googleapis.com/v1beta/models/'.$model.':generateContent?key='.$key;
        $payload = [
            'systemInstruction' => [
                'parts' => [['text' => $system]],
            ],
            'contents' => [
                ['role' => 'user', 'parts' => [['text' => $prompt]]],
            ],
            'generationConfig' => [
                'responseMimeType' => 'application/json',
                'maxOutputTokens' => 600,
                'temperature' => 0.2,
            ],
        ];

        try {
            $res = Http::timeout(20)->post($endpoint, $payload);
            if (! $res->ok() && $model !== 'gemini-flash-lite-latest') {
                $fallback = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-lite-latest:generateContent?key='.$key;
                $res = Http::timeout(20)->post($fallback, $payload);
            }

            if ($res->ok()) {
                $raw = $res->json('candidates.0.content.parts.0.text');
                $parsed = json_decode($raw, true);
                if (is_array($parsed) && count($parsed) > 0) {
                    return array_values(array_filter(array_map('trim', $parsed)));
                }
            }
        } catch (\Throwable $e) {
            report($e);
        }

        return [];
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
            $model = config('services.ai.model') ?: 'gemini-flash-lite-latest';
            $endpoint = 'https://generativelanguage.googleapis.com/v1beta/models/'.$model.':generateContent?key='.$key;
            $payload = [
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
            ];

            $res = Http::timeout(30)->post($endpoint, $payload);

            // Fallback to gemini-flash-lite-latest if spike/error on primary model
            if (! $res->ok() && $model !== 'gemini-flash-lite-latest') {
                $fallbackEndpoint = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-lite-latest:generateContent?key='.$key;
                $res = Http::timeout(30)->post($fallbackEndpoint, $payload);
            }

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
