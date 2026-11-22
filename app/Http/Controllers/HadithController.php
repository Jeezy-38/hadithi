<?php

namespace App\Http\Controllers;

use App\Models\Hadith;
use App\Services\Research\HadithSimilarity;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class HadithController extends Controller
{
    public function show(Hadith $hadith, HadithSimilarity $similarity): View
    {
        abort_unless($hadith->is_published, 404);
        $hadith->load('chapter.book.collection');

        $related = Hadith::visible()
            ->where('chapter_id', $hadith->chapter_id)
            ->where('id', '!=', $hadith->id)
            ->orderBy('id')
            ->limit(4)
            ->get();

        // Hadith iliyotangulia na inayofuata kwa ajili ya usomaji mfululizo
        $prev = Hadith::visible()->where('chapter_id', $hadith->chapter_id)->where('id', '<', $hadith->id)->orderByDesc('id')->first()
            ?? Hadith::visible()->where('id', '<', $hadith->id)->orderByDesc('id')->first();
        $next = Hadith::visible()->where('chapter_id', $hadith->chapter_id)->where('id', '>', $hadith->id)->orderBy('id')->first()
            ?? Hadith::visible()->where('id', '>', $hadith->id)->orderBy('id')->first();

        try {
            $similar = $similarity->for($hadith, 3);
        } catch (\Throwable $e) {
            $similar = collect();
        }

        return view('hadith', compact('hadith', 'related', 'prev', 'next', 'similar'));
    }

    public function today(): RedirectResponse
    {
        $total = Hadith::published()->count();
        abort_if($total === 0, 404);

        $dayIndex = (int) now()->format('z'); // stable per calendar day, changes at midnight
        $hadith = Hadith::published()->orderBy('id')->skip($dayIndex % $total)->first();

        return redirect()->route('hadith.show', $hadith);
    }
}
