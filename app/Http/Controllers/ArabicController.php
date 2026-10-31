<?php

namespace App\Http\Controllers;

use App\Models\Hadith;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ArabicController extends Controller
{
    /** Chagua hadith, kisha bonyeza neno lolote la Kiarabu. */
    public function index(Request $request, ?Hadith $hadith = null)
    {
        $q = Str::limit(trim((string) $request->query('q', '')), 100, '');
        $results = collect();
        if (! $hadith && mb_strlen($q) >= 2) {
            $query = Hadith::published()->with('chapter.book.collection');
            ctype_digit($q) ? $query->where('number', $q) : $query->search($q);
            $results = $query->limit(15)->get();
        }
        if ($hadith && ! $hadith->is_published) {
            abort(404);
        }
        $words = $hadith ? preg_split('/\s+/u', trim((string) $hadith->arabic), -1, PREG_SPLIT_NO_EMPTY) : [];

        return view('arabic.index', compact('q', 'results', 'hadith', 'words'));
    }

    /** Hadith zenye neno hili (bila harakat). */
    public function word(Request $request)
    {
        $w = Hadith::normalize(Str::limit(trim((string) $request->query('w', '')), 40, ''));
        $w = preg_replace('/[^\p{Arabic}]/u', '', $w);
        if (mb_strlen($w) < 2) {
            return response()->json(['word' => $w, 'total' => 0, 'items' => []]);
        }
        $like = '%'.str_replace(['!', '%', '_'], ['!!', '!%', '!_'], $w).'%';
        $base = Hadith::published()->whereRaw("search_arabic like ? escape '!'", [$like]);
        $total = (clone $base)->count();
        $items = $base->with('chapter.book.collection')->limit(8)->get()->map(fn ($h) => [
            'id' => $h->id,
            'number' => $h->number,
            'title' => Str::limit((string) $h->title, 70),
            'url' => route('hadith.show', $h),
            'ref' => trim(($h->chapter?->book?->collection?->name ?? '').' · '.$h->number, ' ·'),
        ]);

        return response()->json(['word' => $w, 'total' => $total, 'items' => $items]);
    }
}
