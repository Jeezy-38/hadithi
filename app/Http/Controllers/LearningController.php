<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Hadith;
use App\Models\LearningDay;
use App\Models\MemorizationCard;
use App\Services\Learning\QuizBuilder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LearningController extends Controller
{
    /** Orodha ya kozi (kila kitabu = kozi moja, kila hadith = somo). */
    public function index()
    {
        $courses = Book::query()
            ->join('collections', 'collections.id', '=', 'books.collection_id')
            ->join('chapters', 'chapters.book_id', '=', 'books.id')
            ->join('hadiths', 'hadiths.chapter_id', '=', 'chapters.id')
            ->where('hadiths.is_published', true)
            ->groupBy('books.id', 'books.number', 'books.title_sw', 'books.title_ar', 'collections.id', 'collections.slug', 'collections.name', 'collections.name_ar')
            ->orderBy('collections.id')->orderBy('books.number')
            ->get([
                'books.id', 'books.number', 'books.title_sw', 'books.title_ar',
                'collections.slug as collection_slug', 'collections.name as collection_name', 'collections.name_ar as collection_name_ar',
                DB::raw('count(hadiths.id) as lessons'),
            ]);

        $user = auth()->user();
        $done = collect();
        $stats = null;
        if ($user) {
            $done = DB::table('learning_progress')
                ->join('hadiths', 'hadiths.id', '=', 'learning_progress.hadith_id')
                ->join('chapters', 'chapters.id', '=', 'hadiths.chapter_id')
                ->where('learning_progress.user_id', $user->id)
                ->selectRaw('chapters.book_id as book_id, count(*) as c')
                ->groupBy('chapters.book_id')
                ->pluck('c', 'book_id');

            $stats = [
                'streak' => LearningDay::streakFor($user->id),
                'learned' => DB::table('learning_progress')->where('user_id', $user->id)->count(),
                'cards' => MemorizationCard::where('user_id', $user->id)->count(),
                'due' => MemorizationCard::where('user_id', $user->id)->where('due_at', '<=', now())->count(),
            ];
        }

        return view('learning.index', [
            'groups' => $courses->groupBy('collection_slug'),
            'done' => $done,
            'stats' => $stats,
        ]);
    }

    public function course(Book $book)
    {
        $book->load('collection');
        $lessons = Hadith::published()
            ->select('hadiths.*')
            ->join('chapters', 'chapters.id', '=', 'hadiths.chapter_id')
            ->where('chapters.book_id', $book->id)
            ->with('chapter')
            ->orderBy('chapters.number')->orderBy('hadiths.id')
            ->paginate(40);

        abort_if($lessons->total() === 0, 404);

        $done = auth()->check()
            ? DB::table('learning_progress')->where('user_id', auth()->id())
                ->whereIn('hadith_id', $lessons->pluck('id'))->pluck('hadith_id')->all()
            : [];
        $doneTotal = auth()->check()
            ? DB::table('learning_progress')
                ->join('hadiths', 'hadiths.id', '=', 'learning_progress.hadith_id')
                ->join('chapters', 'chapters.id', '=', 'hadiths.chapter_id')
                ->where('learning_progress.user_id', auth()->id())->where('chapters.book_id', $book->id)->count()
            : 0;

        return view('learning.course', compact('book', 'lessons', 'done', 'doneTotal'));
    }

    public function lesson(Hadith $hadith)
    {
        abort_unless($hadith->is_published, 404);
        $hadith->load('chapter.book.collection');
        $book = $hadith->chapter->book;

        $base = fn () => Hadith::published()->select('hadiths.*')
            ->join('chapters', 'chapters.id', '=', 'hadiths.chapter_id')
            ->where('chapters.book_id', $book->id);
        $position = fn ($h) => [$h->chapter->number ?? 0, $h->id];
        [$chNo, $id] = $position($hadith);

        $prev = $base()->where(fn ($q) => $q->where('chapters.number', '<', $chNo)
            ->orWhere(fn ($q2) => $q2->where('chapters.number', $chNo)->where('hadiths.id', '<', $id)))
            ->orderByDesc('chapters.number')->orderByDesc('hadiths.id')->first();
        $next = $base()->where(fn ($q) => $q->where('chapters.number', '>', $chNo)
            ->orWhere(fn ($q2) => $q2->where('chapters.number', $chNo)->where('hadiths.id', '>', $id)))
            ->orderBy('chapters.number')->orderBy('hadiths.id')->first();

        $user = auth()->user();
        $completed = $user ? DB::table('learning_progress')->where('user_id', $user->id)->where('hadith_id', $hadith->id)->exists() : false;
        $hasCard = $user ? MemorizationCard::where('user_id', $user->id)->where('hadith_id', $hadith->id)->exists() : false;

        return view('learning.lesson', compact('hadith', 'book', 'prev', 'next', 'completed', 'hasCard'));
    }

    public function toggleComplete(Hadith $hadith): JsonResponse
    {
        abort_unless($hadith->is_published, 404);
        $uid = auth()->id();
        $existing = DB::table('learning_progress')->where('user_id', $uid)->where('hadith_id', $hadith->id);

        if ($existing->exists()) {
            $existing->delete();
            $completed = false;
        } else {
            DB::table('learning_progress')->insert([
                'user_id' => $uid, 'hadith_id' => $hadith->id,
                'completed_at' => now(), 'created_at' => now(), 'updated_at' => now(),
            ]);
            LearningDay::touchToday($uid);
            $completed = true;
        }

        return response()->json(['completed' => $completed]);
    }

    public function quiz(?Book $book = null)
    {
        return view('learning.quiz', ['book' => $book?->load('collection')]);
    }

    public function quizData(?Book $book = null, QuizBuilder $builder = null): JsonResponse
    {
        $builder ??= app(QuizBuilder::class);

        return response()->json(['questions' => $builder->build($book, 10)]);
    }

    public function activity(): JsonResponse
    {
        LearningDay::touchToday(auth()->id());

        return response()->json(['ok' => true]);
    }

    public function memorize()
    {
        $user = auth()->user();
        $due = $user ? MemorizationCard::where('user_id', $user->id)->where('due_at', '<=', now())->count() : 0;
        $total = $user ? MemorizationCard::where('user_id', $user->id)->count() : 0;

        return view('learning.memorize', compact('due', 'total'));
    }

    public function memorizeData(): JsonResponse
    {
        $cards = MemorizationCard::where('user_id', auth()->id())
            ->where('due_at', '<=', now())
            ->with('hadith.chapter.book.collection')
            ->orderBy('due_at')
            ->limit(20)
            ->get()
            ->filter(fn ($c) => $c->hadith && $c->hadith->is_published)
            ->map(fn ($c) => [
                'id' => $c->hadith_id,
                'title' => $c->hadith->title ?: Str::limit($c->hadith->swahili, 120),
                'arabic' => $c->hadith->arabic,
                'swahili' => $c->hadith->swahili,
                'ref' => trim(($c->hadith->chapter->book->collection->name ?? '').' · Na. '.$c->hadith->number),
                'url' => route('hadith.show', $c->hadith),
                'reps' => $c->repetitions,
            ])->values();

        return response()->json(['cards' => $cards]);
    }

    public function addCard(Hadith $hadith): JsonResponse
    {
        abort_unless($hadith->is_published, 404);
        $card = MemorizationCard::firstOrCreate(
            ['user_id' => auth()->id(), 'hadith_id' => $hadith->id],
            ['due_at' => now()]
        );

        return response()->json(['added' => true, 'new' => $card->wasRecentlyCreated]);
    }

    public function reviewCard(Request $request, Hadith $hadith): JsonResponse
    {
        $data = $request->validate(['grade' => ['required', 'integer', 'between:0,3']]);
        $card = MemorizationCard::where('user_id', auth()->id())->where('hadith_id', $hadith->id)->firstOrFail();
        $card->applyGrade((int) $data['grade']);
        LearningDay::touchToday(auth()->id());

        return response()->json([
            'due_at' => $card->due_at->toIso8601String(),
            'interval_days' => $card->interval_days,
        ]);
    }
}
