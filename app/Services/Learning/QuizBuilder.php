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
    public function build(?Book $book, int $count = 10): array
    {
        $query = Hadith::published()
            ->select('hadiths.*')
            ->with('chapter.book.collection')
            ->whereRaw('CHAR_LENGTH(hadiths.swahili) BETWEEN 60 AND 700');

        if ($book) {
            $query->join('chapters', 'chapters.id', '=', 'hadiths.chapter_id')
                ->where('chapters.book_id', $book->id);
        }

        $pool = $query->inRandomOrder()->limit(80)->get();
        if ($pool->count() < 4) {
            return [];
        }

        $collections = Collection::query()->orderBy('id')->get();
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
        $words = preg_split('/\s+/u', trim($hadith->swahili), -1, PREG_SPLIT_NO_EMPTY);
        $n = count($words);
        if ($n < 12) {
            return null;
        }

        $candidates = [];
        for ($k = (int) floor($n * 0.3); $k < $n; $k++) {
            $clean = $this->cleanWord($words[$k]);
            if (mb_strlen($clean) >= 6 && ! preg_match('/[\d\/]/u', $clean)) {
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
            ->flatMap(fn ($h) => preg_split('/\s+/u', $h->swahili, -1, PREG_SPLIT_NO_EMPTY))
            ->map(fn ($w) => $this->cleanWord($w))
            ->filter(fn ($w) => abs(mb_strlen($w) - $len) <= 2 && mb_strtolower($w) !== mb_strtolower($answer) && ! preg_match('/[\d\/]/u', $w))
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
            Str::limit($hadith->swahili, 130),
            $others->map(fn ($h) => Str::limit($h->swahili, 130))->values()->all(),
            $hadith
        );
    }

    private function source(Hadith $hadith, Coll $collections): ?array
    {
        $own = $hadith->chapter?->book?->collection?->name;
        if (! $own || $collections->count() < 2) {
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
