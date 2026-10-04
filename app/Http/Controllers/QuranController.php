<?php

namespace App\Http\Controllers;

use App\Services\QuranService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class QuranController extends Controller
{
    public function show(int $number, QuranService $service): View
    {
        if ($number < 1 || $number > 114) {
            abort(404, 'Sura haikupatikana.');
        }

        $surah = $service->getSurah($number);
        if (! $surah) {
            abort(404, 'Sura haikupatikana.');
        }

        $navigation = $service->getNavigation($number);

        return view('quran-show', [
            'surah' => $surah,
            'ayahs' => $surah->ayahs,
            'prev' => $navigation['prev'],
            'next' => $navigation['next'],
        ]);
    }
}
