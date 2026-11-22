<?php

namespace App\Http\Controllers;

use App\Models\Hadith;
use App\Models\LearningDay;
use App\Models\MemorizationCard;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    /** Wasifu na Dashibodi ya mtumiaji aliyeingia: taarifa za akaunti na takwimu za kusoma. */
    public function show()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $stats = [
            'streak' => LearningDay::streakFor($user->id),
            'learned' => DB::table('learning_progress')->where('user_id', $user->id)->count(),
            'cards' => MemorizationCard::where('user_id', $user->id)->count(),
            'due' => MemorizationCard::where('user_id', $user->id)->where('due_at', '<=', now())->count(),
            'bookmarks' => $user->bookmarks()->count(),
        ];

        // Lengo la mwezi na hesabu ya mwezi huu
        $monthCount = DB::table('learning_progress')
            ->where('user_id', $user->id)
            ->where('completed_at', '>=', now()->startOfMonth())
            ->count();
        $monthGoal = max(20, $monthCount > 0 ? (int) (ceil($monthCount / 10) * 10) : 20);

        // Siku 7 za wiki hii (Jumatatu - Jumapili) kwa ajili ya streak tracker
        $startOfWeek = now('Africa/Dar_es_Salaam')->startOfWeek();
        $activeDays = LearningDay::where('user_id', $user->id)
            ->where('day', '>=', $startOfWeek->toDateString())
            ->pluck('day')
            ->map(fn ($d) => $d instanceof \Carbon\CarbonInterface ? $d->toDateString() : (string) $d)
            ->toArray();

        $weekDays = [];
        $dayLabels = ['J', 'J', 'T', 'A', 'I', 'J', 'J']; // Jt, Jn, Tt, Ah, Ij, Jm, Jp
        $dayNames = ['Jumatatu', 'Jumanne', 'Jumatano', 'Alhamisi', 'Ijumaa', 'Jumamosi', 'Jumapili'];

        for ($i = 0; $i < 7; $i++) {
            $cur = (clone $startOfWeek)->addDays($i);
            $curDate = $cur->toDateString();
            $isToday = $curDate === now('Africa/Dar_es_Salaam')->toDateString();
            $isFuture = $cur->isFuture() && ! $isToday;

            $weekDays[] = [
                'date' => $curDate,
                'letter' => $dayLabels[$i],
                'name' => $dayNames[$i],
                'active' => in_array($curDate, $activeDays, true),
                'is_today' => $isToday,
                'is_future' => $isFuture,
            ];
        }

        // Masomo/Hadithi za hivi karibuni
        $recent = Hadith::published()
            ->with('chapter.book.collection')
            ->select('hadiths.*')
            ->join('learning_progress', 'learning_progress.hadith_id', '=', 'hadiths.id')
            ->where('learning_progress.user_id', $user->id)
            ->orderByDesc('learning_progress.completed_at')
            ->limit(4)
            ->get();

        $isStarterRecent = false;
        if ($recent->isEmpty()) {
            // Ikiwa mtumiaji bado hajakamilisha hadithi, mpe hadithi 3 za kuanzia (mf. Nawawi 40 au Bukhari)
            $recent = Hadith::published()
                ->with('chapter.book.collection')
                ->whereHas('chapter.book.collection', function ($q) {
                    $q->whereIn('slug', ['nawawi40', 'bukhari', 'muslim']);
                })
                ->orderBy('id')
                ->limit(3)
                ->get();
            $isStarterRecent = true;
        }

        // Mapendekezo ya Hadithi kulingana na makusanyo au masomo
        $recommendations = Hadith::published()
            ->with('chapter.book.collection')
            ->whereNotIn('id', $recent->pluck('id')->all())
            ->orderByDesc('id')
            ->limit(4)
            ->get();

        return view('profile', compact(
            'user',
            'stats',
            'recent',
            'isStarterRecent',
            'monthCount',
            'monthGoal',
            'weekDays',
            'recommendations'
        ));
    }
}
