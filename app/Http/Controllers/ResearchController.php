<?php

namespace App\Http\Controllers;

use App\Livewire\TopicIndex;
use App\Models\Hadith;
use App\Services\Research\HadithSimilarity;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ResearchController extends Controller
{
    /** Ukurasa mkuu: tafuta hadith, kisha fungua ramani au linganisha. */
    public function hub(Request $request)
    {
        $q = Str::limit(trim((string) $request->query('q', '')), 100, '');
        $pickedId = (int) $request->query('a');
        $picked = $pickedId ? Hadith::published()->with('chapter.book.collection')->find($pickedId) : null;

        $results = collect();
        if (mb_strlen($q) >= 2) {
            $query = Hadith::published()->with('chapter.book.collection');
            ctype_digit($q) ? $query->where('number', $q) : $query->search($q);
            $results = $query->limit(20)->get();
        }

        return view('research.hub', compact('q', 'picked', 'results'));
    }

    public function compare(Request $request)
    {
        $a = Hadith::published()->with('chapter.book.collection')->find((int) $request->query('a'));
        $b = Hadith::published()->with('chapter.book.collection')->find((int) $request->query('b'));
        abort_unless($a && $b, 404);

        $ta = HadithSimilarity::tokens((string) $a->swahili);
        $tb = HadithSimilarity::tokens((string) $b->swahili);
        $shared = array_values(array_intersect($ta, $tb));
        usort($shared, fn ($x, $y) => mb_strlen($y) <=> mb_strlen($x));
        $score = ($ta && $tb) ? round(count($shared) / sqrt(count($ta) * count($tb)) * 100) : 0;

        return view('research.compare', [
            'a' => $a, 'b' => $b,
            'shared' => array_slice($shared, 0, 20),
            'score' => (int) $score,
        ]);
    }

    public function graph(Hadith $hadith, HadithSimilarity $similarity)
    {
        abort_unless($hadith->is_published, 404);
        $hadith->load('chapter.book.collection');

        $similar = $similarity->for($hadith, 6);

        // Mada zinazohusiana (kwa maneno ya mada yaliyopo).
        $hay = mb_strtolower($hadith->swahili.' '.$hadith->arabic.' '.$hadith->english);
        $topics = collect(TopicIndex::getIslamicTopics())
            ->filter(fn ($t) => collect($t['terms'])->contains(fn ($term) => str_contains($hay, mb_strtolower($term))))
            ->take(4)->values();

        // Nodi zinazozunguka hadith ya kati.
        $ring = [];
        $book = $hadith->chapter->book;
        $ring[] = ['type' => 'book', 'label' => Str::limit($book->title_sw, 14), 'full' => $book->collection->name.' · '.$book->title_sw, 'href' => route('learning.course', $book->id)];
        $ring[] = ['type' => 'chapter', 'label' => Str::limit($hadith->chapter->title_sw, 14), 'full' => $hadith->chapter->title_sw, 'href' => route('hadith.show', $hadith)];
        foreach ($topics as $t) {
            $ring[] = ['type' => 'topic', 'label' => Str::limit($t['badge'], 14), 'full' => $t['title_sw'], 'href' => route('topics.show', $t['slug'])];
        }
        foreach ($similar as $s) {
            $ring[] = ['type' => 'similar', 'label' => 'Na. '.$s['hadith']->number, 'full' => Str::limit($s['hadith']->swahili, 110), 'href' => route('research.graph', $s['hadith']), 'hadith' => $s['hadith'], 'score' => $s['score'], 'shared' => $s['shared']];
        }

        $n = count($ring);
        foreach ($ring as $i => &$node) {
            $angle = -M_PI / 2 + 2 * M_PI * $i / max(1, $n);
            $node['x'] = round(180 + 125 * cos($angle), 1);
            $node['y'] = round(180 + 125 * sin($angle), 1);
        }
        unset($node);

        return view('research.graph', compact('hadith', 'ring', 'similar', 'topics'));
    }
}
