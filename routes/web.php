<?php
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
    return view('hadith', compact('hadith', 'related'));
})->name('hadith.show');

Route::get('/hadith-ya-leo', function () {
    $total = Hadith::published()->count();
    abort_if($total === 0, 404);
    $dayIndex = (int) now()->format('z'); // stable per calendar day, changes at midnight
    $hadith = Hadith::published()->orderBy('id')->skip($dayIndex % $total)->first();
    return redirect()->route('hadith.show', $hadith);
})->name('hadith.today');

Route::get('/vipendwa', fn () => view('bookmarks'))->name('bookmarks');

Route::get('/vipendwa/data', function (Request $request) {
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

    return response()->json($hadiths->map(fn ($hadith) => [
        'id' => $hadith->id,
        'number' => $hadith->number,
        'collection' => $hadith->chapter->book->collection->name,
        'book' => $hadith->chapter->book->title_sw,
        'chapter' => $hadith->chapter->title_sw,
        'excerpt' => Str::limit($hadith->swahili, 160),
        'url' => route('hadith.show', $hadith),
    ]));
})->name('bookmarks.data');
