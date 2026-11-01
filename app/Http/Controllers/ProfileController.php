<?php

namespace App\Http\Controllers;

use App\Models\Hadith;
use App\Models\LearningDay;
use App\Models\MemorizationCard;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    /** Wasifu wa mtumiaji aliyeingia: taarifa za akaunti na takwimu za kujifunza. */
    public function show()
    {
        $user = auth()->user();

        $stats = [
            'streak' => LearningDay::streakFor($user->id),
            'learned' => DB::table('learning_progress')->where('user_id', $user->id)->count(),
            'cards' => MemorizationCard::where('user_id', $user->id)->count(),
            'due' => MemorizationCard::where('user_id', $user->id)->where('due_at', '<=', now())->count(),
            'bookmarks' => $user->bookmarks()->count(),
        ];

        $recent = Hadith::published()
            ->select('hadiths.*')
            ->join('learning_progress', 'learning_progress.hadith_id', '=', 'hadiths.id')
            ->where('learning_progress.user_id', $user->id)
            ->orderByDesc('learning_progress.completed_at')
            ->limit(5)
            ->get();

        return view('profile', compact('user', 'stats', 'recent'));
    }
}
