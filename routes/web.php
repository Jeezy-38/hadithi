<?php
use App\Http\Controllers\Auth\EmailAuthController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\HadithAudioController;
use App\Livewire\Library;
use App\Models\Hadith;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

Route::get('/', Library::class)->name('library');

Route::get('/hadith/{hadith}', function (Hadith $hadith) {
    abort_unless($hadith->is_published, 404);
    $hadith->load('chapter.book.collection');
    $related = Hadith::published()
        ->where('chapter_id', $hadith->chapter_id)
        ->where('id', '!=', $hadith->id)
        ->orderBy('id')
        ->limit(4)
        ->get();
    // Hadith inayofuata kwa "kusoma mfululizo": ndani ya mlango huu, vinginevyo inayofuata kwa ID.
    $next = Hadith::published()->where('chapter_id', $hadith->chapter_id)->where('id', '>', $hadith->id)->orderBy('id')->first()
        ?? Hadith::published()->where('id', '>', $hadith->id)->orderBy('id')->first();
    return view('hadith', compact('hadith', 'related', 'next'));
})->name('hadith.show');

Route::get('/hadith-ya-leo', function () {
    $total = Hadith::published()->count();
    abort_if($total === 0, 404);
    $dayIndex = (int) now()->format('z'); // stable per calendar day, changes at midnight
    $hadith = Hadith::published()->orderBy('id')->skip($dayIndex % $total)->first();
    return redirect()->route('hadith.show', $hadith);
})->name('hadith.today');

Route::get('/vipendwa', fn () => view('bookmarks'))->name('bookmarks');

Route::get('/vipendwa/ids', function () {
    return response()->json([
        'authenticated' => auth()->check(),
        'ids' => auth()->check() ? auth()->user()->bookmarks()->pluck('hadiths.id')->values() : [],
    ]);
})->name('bookmarks.ids');

Route::get('/vipendwa/data', function (Request $request) {
    if (auth()->check()) {
        $guestIds = collect(explode(',', (string) $request->query('ids')))
            ->map(fn ($id) => (int) trim($id))
            ->filter()
            ->unique();

        if ($guestIds->isNotEmpty()) {
            $validGuestIds = Hadith::published()->whereIn('id', $guestIds)->pluck('id');
            auth()->user()->bookmarks()->syncWithoutDetaching($validGuestIds);
        }

        $hadiths = auth()->user()->bookmarks()
            ->published()
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

        $hadiths = Hadith::published()
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
        'excerpt' => Str::limit($hadith->swahili, 160),
        'url' => route('hadith.show', $hadith),
    ]));
})->name('bookmarks.data');

Route::post('/vipendwa/toggle/{hadith}', function (Hadith $hadith) {
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
})->middleware('auth')->name('bookmarks.toggle');

Route::post('/vipendwa/sync', function (Request $request) {
    $user = auth()->user();
    if (! $user) {
        return response()->json(['error' => 'Unauthenticated'], 401);
    }

    $ids = collect($request->input('ids', []))
        ->map(fn ($id) => (int) $id)
        ->filter()
        ->unique();

    if ($ids->isNotEmpty()) {
        $validIds = Hadith::published()->whereIn('id', $ids)->pluck('id');
        $user->bookmarks()->syncWithoutDetaching($validIds);
    }

    return response()->json([
        'ids' => $user->bookmarks()->pluck('hadiths.id')->values(),
        'count' => $user->bookmarks()->count(),
    ]);
})->middleware('auth')->name('bookmarks.sync');

// Social login — fomu iko kwenye drawer ya pembeni (layout), /login huifungua tu.
Route::get('/login', fn () => redirect()->route('library', ['login' => 1]))->name('login');

Route::middleware(['guest', 'throttle:20,1'])->group(function () {
    Route::get('/auth/{provider}/redirect', [SocialAuthController::class, 'redirect'])
        ->whereIn('provider', SocialAuthController::PROVIDERS)->name('auth.redirect');
    Route::get('/auth/{provider}/callback', [SocialAuthController::class, 'callback'])
        ->whereIn('provider', SocialAuthController::PROVIDERS)->name('auth.callback');

    // Email + nenosiri
    Route::post('/auth/register', [EmailAuthController::class, 'register'])->name('auth.register');
    Route::post('/auth/login', [EmailAuthController::class, 'login'])->name('auth.login');

    // Umesahau nenosiri?
    Route::post('/auth/forgot', [PasswordResetController::class, 'sendLink'])->name('password.email');
    Route::get('/auth/reset/{token}', [PasswordResetController::class, 'showReset'])->name('password.reset');
    Route::post('/auth/reset', [PasswordResetController::class, 'reset'])->name('password.update');
});

Route::post('/logout', [SocialAuthController::class, 'logout'])->middleware('auth')->name('logout');

// Sauti ya hadith (TTS) — throttle kwa sababu kutengeneza sauti mpya ni gharama.
Route::get('/audio/hadith/{hadith}/{lang}', HadithAudioController::class)
    ->middleware('throttle:30,1')
    ->name('hadith.audio');
