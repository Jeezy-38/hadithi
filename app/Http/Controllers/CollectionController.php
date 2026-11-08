<?php

namespace App\Http\Controllers;

use App\Livewire\Library;
use App\Models\Book;
use App\Models\Chapter;
use App\Models\Collection;
use App\Models\Hadith;
use Illuminate\Support\Facades\DB;

/**
 * Kuvinjari mkusanyo: Makusanyo -> Vitabu -> Mada (sura) -> Hadith.
 */
class CollectionController extends Controller
{
    /** Ukurasa wa mkusanyo: orodha ya vitabu vyake. */
    public function show(Collection $collection)
    {
        $stats = DB::table('hadiths')
            ->join('chapters', 'hadiths.chapter_id', '=', 'chapters.id')
            ->join('books', 'chapters.book_id', '=', 'books.id')
            ->where('books.collection_id', $collection->id)
            ->where('hadiths.is_published', true)
            ->groupBy('books.id')
            ->selectRaw('books.id as book_id, count(hadiths.id) as hadiths, count(distinct chapters.id) as chapters')
            ->get()
            ->keyBy('book_id');

        $books = Book::where('collection_id', $collection->id)
            ->whereIn('id', $stats->keys())
            ->orderBy('number')
            ->get();

        $meta = (new Library())->getNarratorMeta()[$collection->slug] ?? [];

        return view('collections.show', [
            'collection' => $collection,
            'books' => $books,
            'stats' => $stats,
            'meta' => $meta,
            'totalHadiths' => (int) $stats->sum('hadiths'),
        ]);
    }

    /** Ukurasa wa kitabu: orodha ya mada (sura) zake. */
    public function book(Collection $collection, Book $book)
    {
        abort_unless($book->collection_id === $collection->id, 404);

        $stats = DB::table('hadiths')
            ->join('chapters', 'hadiths.chapter_id', '=', 'chapters.id')
            ->where('chapters.book_id', $book->id)
            ->where('hadiths.is_published', true)
            ->groupBy('chapters.id')
            ->selectRaw('chapters.id as chapter_id, count(hadiths.id) as hadiths')
            ->get()
            ->keyBy('chapter_id');

        $chapters = Chapter::where('book_id', $book->id)
            ->whereIn('id', $stats->keys())
            ->orderBy('number')
            ->get();

        return view('collections.book', [
            'collection' => $collection,
            'book' => $book,
            'chapters' => $chapters,
            'stats' => $stats,
            'totalHadiths' => (int) $stats->sum('hadiths'),
        ]);
    }

    /** Ukurasa wa mada (sura): hadith zake. */
    public function chapter(Collection $collection, Book $book, Chapter $chapter)
    {
        abort_unless($book->collection_id === $collection->id && $chapter->book_id === $book->id, 404);

        $hadiths = Hadith::visible()
            ->with('chapter.book.collection')
            ->where('chapter_id', $chapter->id)
            ->orderBy('id')
            ->simplePaginate(20);

        abort_if($hadiths->isEmpty() && $hadiths->currentPage() === 1, 404);

        return view('collections.chapter', [
            'collection' => $collection,
            'book' => $book,
            'chapter' => $chapter,
            'hadiths' => $hadiths,
        ]);
    }
}
