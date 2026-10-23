<?php

namespace App\Http\Controllers;

use App\Models\Hadith;
use App\Models\PushSubscription;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PushNotificationController extends Controller
{
    /**
     * Subscribe a client browser / PWA for daily Hadith notifications.
     */
    public function subscribe(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'endpoint' => ['required', 'string', 'max:500'],
            'keys.p256dh' => ['nullable', 'string', 'max:255'],
            'keys.auth' => ['nullable', 'string', 'max:255'],
            'content_encoding' => ['nullable', 'string', 'max:32'],
        ]);

        $sub = PushSubscription::updateOrCreate(
            ['endpoint' => $validated['endpoint']],
            [
                'user_id' => auth()->id(),
                'public_key' => $validated['keys']['p256dh'] ?? null,
                'auth_token' => $validated['keys']['auth'] ?? null,
                'content_encoding' => $validated['content_encoding'] ?? 'aes128gcm',
                'is_active' => true,
            ]
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Umesajiliwa kikamilifu kupokea Hadithi ya Siku kila asubuhi.',
            'id' => $sub->id,
        ]);
    }

    /**
     * Unsubscribe from daily notifications.
     */
    public function unsubscribe(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'endpoint' => ['required', 'string'],
        ]);

        PushSubscription::where('endpoint', $validated['endpoint'])
            ->update(['is_active' => false]);

        return response()->json([
            'status' => 'success',
            'message' => 'Arifa za kila siku zimezimwa.',
        ]);
    }

    /**
     * Get today's featured Hadith payload for notifications and PWA display.
     */
    public function todayHadith(): JsonResponse
    {
        $total = Hadith::published()->count();
        if ($total === 0) {
            return response()->json([
                'title' => 'Hadithi ya Siku',
                'body' => 'Karibu Bayt Al-Hadith usome Sunnah za Mtume ﷺ.',
                'url' => route('library'),
            ]);
        }

        $dayIndex = (int) now()->format('z');
        $hadith = Hadith::published()
            ->with('chapter.book.collection')
            ->orderBy('id')
            ->skip($dayIndex % $total)
            ->first();

        $collectionName = $hadith->chapter->book->collection->name ?? 'Bayt Al-Hadith';
        $shortSwahili = \Illuminate\Support\Str::limit($hadith->swahili, 140);

        return response()->json([
            'id' => $hadith->id,
            'title' => 'Hadithi ya Siku · ' . $collectionName,
            'body' => '“' . $shortSwahili . '”',
            'arabic' => $hadith->arabic,
            'swahili' => $hadith->swahili,
            'reference' => $collectionName . ' Na. ' . $hadith->number,
            'url' => route('hadith.show', $hadith),
            'icon' => asset('icons/icon-192.png'),
            'badge' => asset('icons/icon-192.png'),
        ]);
    }
}
