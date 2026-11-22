<?php

namespace App\Http\Controllers;

use App\Models\Hadith;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BookmarkController extends Controller
{
    public function index(): View
    {
        return view('bookmarks');
    }

    public function ids(): JsonResponse
    {
        return response()->json([
            'authenticated' => auth()->check(),
            'ids' => auth()->check() ? auth()->user()->bookmarks()->pluck('hadiths.id')->values() : [],
        ]);
    }

    public function data(Request $request): JsonResponse
    {
        if (auth()->check()) {
            $guestIds = collect(explode(',', (string) $request->query('ids')))
                ->map(fn ($id) => (int) trim($id))
                ->filter()
                ->unique();

            if ($guestIds->isNotEmpty()) {
                $validGuestIds = Hadith::visible()->whereIn('id', $guestIds)->pluck('id');
                auth()->user()->bookmarks()->syncWithoutDetaching($validGuestIds);
            }

            $hadiths = auth()->user()->bookmarks()
                ->visible()
                ->with('chapter.book.collection')
                ->orderByPivot('created_at', 'desc')
                ->get();
        } else {
            $ids = collect(explode(',', (string) $request->query('ids')))
                ->map(fn ($id) => (int) trim($id))
                ->filter()
                ->unique()
                ->take(200)
                ->values();

            $hadiths = Hadith::visible()
                ->whereIn('id', $ids)
                ->with('chapter.book.collection')
                ->get()
                ->sortBy(fn ($hadith) => $ids->search($hadith->id))
                ->values();
        }

        return response()->json($hadiths->map(fn ($hadith) => [
            'id' => $hadith->id,
            'number' => $hadith->number,
            'collection' => $hadith->chapter->book->collection->name,
            'collection_slug' => $hadith->chapter->book->collection->slug,
            'book' => $hadith->chapter->book->title_sw,
            'chapter' => $hadith->chapter->title_sw,
            'excerpt' => Str::limit($hadith->display_text, 160),
            'url' => route('hadith.show', $hadith),
        ]));
    }

    public function toggle(Hadith $hadith): JsonResponse
    {
        abort_unless($hadith->is_published, 404);
        $user = auth()->user();
        if (! $user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $changes = $user->bookmarks()->toggle($hadith->id);
        $isBookmarked = count($changes['attached']) > 0;

        return response()->json([
            'bookmarked' => $isBookmarked,
            'count' => $user->bookmarks()->count(),
        ]);
    }

    public function sync(Request $request): JsonResponse
    {
        $user = auth()->user();
        if (! $user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $ids = collect($request->input('ids', []))
            ->map(fn ($id) => (int) $id)
            ->filter()
            ->unique();

        if ($ids->isNotEmpty()) {
            $validIds = Hadith::visible()->whereIn('id', $ids)->pluck('id');
            $user->bookmarks()->syncWithoutDetaching($validIds);
        }

        return response()->json([
            'ids' => $user->bookmarks()->pluck('hadiths.id')->values(),
            'count' => $user->bookmarks()->count(),
        ]);
    }
}
