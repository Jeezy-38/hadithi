<?php

namespace App\Services\Research;

use App\Models\Hadith;
use Illuminate\Support\Facades\Cache;

/**
 * Hadith zinazofanana kwa maneno yanayoshirikiana (si hukumu ya kielimu).
 * Inahesabiwa papo hapo na kuhifadhiwa kwenye cache kwa siku moja.
 */
class HadithSimilarity
{
    private const STOP = [
        'mtume', 'mwenyezi', 'mungu', 'amesema', 'alisema', 'anasema', 'wasallam', 'rehema', 'amani', 'kwamba',
        'ambaye', 'ambao', 'ambayo', 'alipokuwa', 'alikuwa', 'walikuwa', 'atakayekuwa', 'yeyote', 'wowote', 'yoyote',
        'muhammad', 'allah', 'swallallahu', 'alayhi', 'kutoka', 'katika', 'kuhusu', 'pamoja', 'kila', 'sana', 'hakika',
        'basi', 'lakini', 'ndani', 'wakati', 'baada', 'kabla', 'hiyo', 'huyo', 'huyu', 'hawa', 'wale', 'alimwambia',
        'nikamwambia', 'akasema', 'wakasema', 'nimesikia', 'amepokewa', 'mpaka', 'mmoja', 'mwingine', 'watu',
    ];

    public static function tokens(string $text): array
    {
        preg_match_all('/\p{L}{5,}/u', mb_strtolower($text), $m);
        $stop = array_flip(self::STOP);

        return array_values(array_unique(array_filter($m[0], fn ($w) => ! isset($stop[$w]))));
    }

    /** @return array<int, array{id:int, score:float, shared:array<int,string>}> */
    public function idsFor(Hadith $hadith, int $limit = 6): array
    {
        $key = "similar:v1:{$hadith->id}:{$limit}";
        try {
            return Cache::remember($key, now()->addDay(), fn () => $this->compute($hadith, $limit));
        } catch (\Throwable $e) {
            return $this->compute($hadith, $limit);
        }
    }

    /** @return \Illuminate\Support\Collection<int, array{hadith:Hadith, score:float, shared:array}> */
    public function for(Hadith $hadith, int $limit = 6)
    {
        $rows = $this->idsFor($hadith, $limit);
        if (! $rows) {
            return collect();
        }
        $models = Hadith::published()->with('chapter.book.collection')
            ->whereIn('id', array_column($rows, 'id'))->get()->keyBy('id');

        return collect($rows)->map(fn ($r) => isset($models[$r['id']])
            ? ['hadith' => $models[$r['id']], 'score' => $r['score'], 'shared' => $r['shared']]
            : null)->filter()->values();
    }

    private function compute(Hadith $hadith, int $limit): array
    {
        $tokens = self::tokens((string) $hadith->swahili);
        if (count($tokens) < 4) {
            return [];
        }

        $probes = collect($tokens)->sortByDesc(fn ($w) => mb_strlen($w))->take(6)->values();
        $candidates = Hadith::published()
            ->where('id', '!=', $hadith->id)
            ->where(function ($q) use ($probes) {
                foreach ($probes as $p) {
                    $q->orWhere('search_swahili', 'like', '%'.addcslashes($p, '%_\\').'%');
                }
            })
            ->select('id', 'swahili')
            ->limit(400)
            ->get();

        $out = [];
        foreach ($candidates as $c) {
            $ct = self::tokens((string) $c->swahili);
            if (! $ct) {
                continue;
            }
            $shared = array_values(array_intersect($tokens, $ct));
            $n = count($shared);
            if ($n < 3) {
                continue;
            }
            $score = $n / sqrt(count($tokens) * count($ct));
            if ($score < 0.1) {
                continue;
            }
            usort($shared, fn ($a, $b) => mb_strlen($b) <=> mb_strlen($a));
            $out[] = ['id' => $c->id, 'score' => round($score, 3), 'shared' => array_slice($shared, 0, 6)];
        }

        usort($out, fn ($a, $b) => $b['score'] <=> $a['score']);

        return array_slice($out, 0, $limit);
    }
}
