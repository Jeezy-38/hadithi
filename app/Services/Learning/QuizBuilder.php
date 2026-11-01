<?php

namespace App\Services\Learning;

use App\Models\Book;
use App\Models\Collection;
use App\Models\Hadith;
use Illuminate\Support\Collection as Coll;
use Illuminate\Support\Str;

/**
 * Hutengeneza maswali ya quiz kutoka kwa hadith zilizo kwenye database
 * (hakuna maudhui ya nje wala yaliyobuniwa).
 */
class QuizBuilder
{
    /** Makusanyo makuu pekee kwa swali la chanzo (Riyadh/Nawawi hunukuu makusanyo haya, hivyo huleta utata). */
    private const MAIN_COLLECTIONS = ['bukhari', 'muslim', 'tirmidhi', 'abudawud', 'nasai', 'ibnmajah', 'ahmad'];

    /** Maneno ya Kiingereza yanayoonyesha tafsiri iliyochanganyika. */
    private const ENGLISH_MARKERS = '/\b(the|of|and|was|that|his|he|she|said|reported|who|with|for|not|is|are|from|when|would|will|you|your)\b/i';

    /**
     * Kauli yenyewe (bila orodha ya wapokezi "Kutoka kwa ... amesema:"), ili maswali yatofautishwe kwa maudhui.
     */
    private function matn(string $s): string
    {
        $s = trim($s);
        if (preg_match('/["“«]/u', $s, $m, PREG_OFFSET_CAPTURE)) {
            $rest = trim(mb_strcut($s, $m[0][1] + strlen($m[0][0])));
            if (mb_strlen($rest) >= 40) {
                return $rest;
            }
        }
        $pos = mb_strrpos($s, ':');
        if ($pos !== false) {
            $rest = trim(mb_substr($s, $pos + 1));
            if (mb_strlen($rest) >= 40) {
                return $rest;
            }
        }

        return $s;
    }

    /** Neno linalofaa kuulizwa: refu vya kutosha, si jina (herufi kubwa mwanzoni), si nambari. */
    private function isPlainWord(string $w): bool
    {
        return mb_strlen($w) >= 6
            && ! preg_match('/[\d\/]/u', $w)
            && mb_strtolower(mb_substr($w, 0, 1)) === mb_substr($w, 0, 1)
            && ! preg_match('/^(amesema|akasema|anasema|kutoka|imepokewa|imepokelewa)$/iu', $w);
    }

    /** Hadith yenye maandishi halisi ya Kiswahili (si kiolezo, si mchanganyiko wa Kiingereza). */
    private function isUsable(Hadith $h): bool
    {
        $s = (string) $h->swahili;
        if (str_starts_with($s, 'Imepokewa katika ') || str_contains($s, 'Mafundisho haya ya Mtume Muhammad')) {
            return false;
        }

        return preg_match_all(self::ENGLISH_MARKERS, $s) < 3;
    }

    /** Je, kitabu hiki kina hadith halisi za kutosha kutengeneza quiz? */
    public function canQuiz(Book $book, int $min = 8): bool
    {
        return Hadith::published()
            ->join('chapters', 'chapters.id', '=', 'hadiths.chapter_id')
            ->where('chapters.book_id', $book->id)
            ->whereRaw('CHAR_LENGTH(hadiths.swahili) BETWEEN 60 AND 700')
            ->where('hadiths.swahili', 'not like', 'Imepokewa katika %')
            ->where('hadiths.swahili', 'not like', '%Mafundisho haya ya Mtume Muhammad%')
            ->select('hadiths.*')
            ->limit(60)
            ->get()
            ->filter(fn (Hadith $h) => $this->isUsable($h))
            ->count() >= $min;
    }

    public function build(?Book $book, int $count = 10): array
    {
        $query = Hadith::published()
            ->select('hadiths.*')
            ->with('chapter.book.collection')
            ->whereRaw('CHAR_LENGTH(hadiths.swahili) BETWEEN 60 AND 700')
            ->where('hadiths.swahili', 'not like', 'Imepokewa katika %')
            ->where('hadiths.swahili', 'not like', '%Mafundisho haya ya Mtume Muhammad%');

        if ($book) {
            $query->join('chapters', 'chapters.id', '=', 'hadiths.chapter_id')
                ->where('chapters.book_id', $book->id);
        }

        $pool = $query->inRandomOrder()->limit(150)->get()
            ->filter(fn (Hadith $h) => $this->isUsable($h))->take(80)->values();
        if ($pool->count() < 4) {
            return [];
        }

        $collections = Collection::query()->whereIn('slug', self::MAIN_COLLECTIONS)->orderBy('id')->get();
        $types = ['cloze', 'title', 'source'];
        $questions = [];
        $i = 0;

        foreach ($pool as $hadith) {
            if (count($questions) >= $count) {
                break;
            }
            // Zungusha aina za maswali; ikishindikana jaribu aina nyingine.
            for ($t = 0; $t < count($types); $t++) {
                $type = $types[($i + $t) % count($types)];
                $q = match ($type) {
                    'cloze' => $this->cloze($hadith, $pool),
                    'title' => $this->title($hadith, $pool),
                    default => $this->source($hadith, $collections),
                };
                if ($q) {
                    $questions[] = $q;
                    break;
                }
            }
            $i++;
        }

        return $questions;
    }

    private function reference(Hadith $hadith): array
    {
        $col = $hadith->chapter?->book?->collection?->name ?? '';

        return [
            'label' => trim($col.' · Na. '.$hadith->number),
            'url' => route('hadith.show', $hadith),
        ];
    }

    private function cleanWord(string $w): string
    {
        return trim($w, " \t\n\r\0\x0B.,;:!?\"'“”‘’()[]{}«»—-–");
    }

    private function cloze(Hadith $hadith, Coll $pool): ?array
    {
        $words = preg_split('/\s+/u', $this->matn((string) $hadith->swahili), -1, PREG_SPLIT_NO_EMPTY);
        $n = count($words);
        if ($n < 12) {
            return null;
        }

        $candidates = [];
        for ($k = (int) floor($n * 0.3); $k < $n; $k++) {
            $clean = $this->cleanWord($words[$k]);
            if ($this->isPlainWord($clean)) {
                $candidates[] = $k;
            }
        }
        if (! $candidates) {
            return null;
        }

        $idx = $candidates[array_rand($candidates)];
        $answer = $this->cleanWord($words[$idx]);
        $len = mb_strlen($answer);

        $distractors = $pool->where('id', '!=', $hadith->id)
            ->flatMap(fn ($h) => preg_split('/\s+/u', $this->matn((string) $h->swahili), -1, PREG_SPLIT_NO_EMPTY))
            ->map(fn ($w) => $this->cleanWord($w))
            ->filter(fn ($w) => abs(mb_strlen($w) - $len) <= 2 && mb_strtolower($w) !== mb_strtolower($answer) && $this->isPlainWord($w))
            ->unique(fn ($w) => mb_strtolower($w))
            ->shuffle()->take(3)->values()->all();
        if (count($distractors) < 3) {
            return null;
        }

        $start = max(0, $idx - 12);
        $end = min($n, $idx + 13);
        $slice = array_slice($words, $start, $end - $start);
        $slice[$idx - $start] = str_replace($this->cleanWord($words[$idx]), '_____', $words[$idx]);
        $text = ($start > 0 ? '… ' : '').implode(' ', $slice).($end < $n ? ' …' : '');

        return $this->pack('cloze', 'pg_lc_q_cloze', 'Jaza neno lililokosekana', $text, $answer, $distractors, $hadith);
    }

    private function title(Hadith $hadith, Coll $pool): ?array
    {
        $title = trim((string) $hadith->title);
        if (mb_strlen($title) < 8 || mb_strlen($title) > 160) {
            return null;
        }
        $others = $pool->where('id', '!=', $hadith->id)->shuffle()->take(3);
        if ($others->count() < 3) {
            return null;
        }

        return $this->pack(
            'title',
            'pg_lc_q_title',
            'Ni hadith ipi inayohusu hili?',
            $title,
            Str::limit($this->matn((string) $hadith->swahili), 130),
            $others->map(fn ($h) => Str::limit($this->matn((string) $h->swahili), 130))->values()->all(),
            $hadith
        );
    }

    private function source(Hadith $hadith, Coll $collections): ?array
    {
        $ownCollection = $hadith->chapter?->book?->collection;
        $own = $ownCollection?->name;
        if (! $own || ! in_array($ownCollection->slug, self::MAIN_COLLECTIONS, true) || $collections->count() < 2) {
            return null;
        }
        $others = $collections->pluck('name')->reject(fn ($n) => $n === $own)->shuffle()->take(3)->values()->all();

        return $this->pack('source', 'pg_lc_q_source', 'Hadith hii inatoka katika makusanyo gani?', Str::limit($hadith->swahili, 260), $own, $others, $hadith);
    }

    private function pack(string $type, string $promptKey, string $prompt, string $text, string $answer, array $wrong, Hadith $hadith): array
    {
        $options = $wrong;
        $options[] = $answer;
        shuffle($options);

        return [
            'type' => $type,
            'prompt_key' => $promptKey,
            'prompt' => $prompt,
            'text' => $text,
            'options' => array_values($options),
            'answer' => array_search($answer, $options, true),
            'ref' => $this->reference($hadith),
        ];
    }
}
