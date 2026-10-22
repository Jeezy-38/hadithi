<?php

namespace App\Livewire;

use App\Models\{Book, Chapter, Collection, Hadith};
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Library extends Component
{
    use WithPagination;

    #[Url(as: 'q', history: true)]
    public string $search = '';

    #[Url(history: true)]
    public string $collection = '';

    #[Url(history: true)]
    public string $book = '';

    #[Url(history: true)]
    public string $chapter = '';

    public function updated($property): void
    {
        if ($property === 'collection') {
            $this->book = $this->chapter = '';
        }
        if ($property === 'book') {
            $this->chapter = '';
        }
        if (in_array($property, ['search', 'collection', 'book', 'chapter'])) {
            $this->resetPage();
        }
    }

    public function chooseCollection(string $slug): void
    {
        abort_unless($slug === '' || Collection::where('slug', $slug)->exists(), 404);
        $this->collection = $slug;
        $this->updated('collection');
    }

    public function clearFilters(): void
    {
        $this->reset('search', 'collection', 'book', 'chapter');
        $this->resetPage();
    }

    public function getNarratorMeta(): array
    {
        return [
            'bukhari' => [
                'author' => 'Imam Muhammad al-Bukhari',
                'author_ar' => 'الإمام البخاري',
                'years' => '194 - 256 H',
                'desc' => 'Kitabu sahihi zaidi baada ya Qur\'ani, chenye hadithi zilizothibitika kwa masharti magumu ya isnad.',
                'badge' => 'Sahih',
            ],
            'muslim' => [
                'author' => 'Imam Muslim ibn al-Hajjaj',
                'author_ar' => 'الإمام مسلم',
                'years' => '204 - 261 H',
                'desc' => 'Makusanyo ya pili kwa usahihi katika Uislamu, yenye mpangilio makini wa mada na sanad.',
                'badge' => 'Sahih',
            ],
            'tirmidhi' => [
                'author' => 'Imam Abu Isa at-Tirmidhi',
                'author_ar' => 'الإمام الترمذي',
                'years' => '209 - 279 H',
                'desc' => 'Kitabu mashuhuri kwa kubainisha daraja za hadithi (Sahih, Hasan, Da\'if) na fikihi za wanavyuoni.',
                'badge' => 'Sunan',
            ],
            'abudawud' => [
                'author' => 'Imam Abu Dawud as-Sijistani',
                'author_ar' => 'الإمام أبو داود',
                'years' => '202 - 275 H',
                'desc' => 'Makusanyo yanayozingatia zaidi hadithi za hukumu (Ahkam) na miongozo ya ibada na muamala.',
                'badge' => 'Sunan',
            ],
            'ahmad' => [
                'author' => 'Imam Ahmad ibn Hanbal',
                'author_ar' => 'الإمام أحمد',
                'years' => '164 - 241 H',
                'desc' => 'Ensaitlopedia kubwa zaidi ya Sunnah, iliyopangwa kwa majina ya Masahaba waliosimulia.',
                'badge' => 'Musnad',
            ],
            'nasai' => [
                'author' => 'Imam Ahmad an-Nasa\'i',
                'author_ar' => 'الإمام النسائي',
                'years' => '214 - 303 H',
                'desc' => 'Sunan as-Sughra, maarufu kwa umakini wa juu na ukali wa kuchuja wapokezi wa hadithi.',
                'badge' => 'Sunan',
            ],
            'ibnmajah' => [
                'author' => 'Imam Ibn Majah al-Qazwini',
                'author_ar' => 'الإمام ابن ماجه',
                'years' => '209 - 273 H',
                'desc' => 'Kimoja cha vitabu sita vikuu (al-Kutub as-Sittah), chenye mpangilio mwepesi wa milango ya fikihi.',
                'badge' => 'Sunan',
            ],
            'nawawi40' => [
                'author' => 'Imam Yahya an-Nawawi',
                'author_ar' => 'الإمام النووي',
                'years' => '631 - 676 H',
                'desc' => 'Hadithi 40 teule zinazobeba misingi mikuu ya Uislamu, imani na kanuni za maisha ya kiroho.',
                'badge' => 'Misingi',
            ],
            'riyadhadussalihin' => [
                'author' => 'Imam Yahya an-Nawawi',
                'author_ar' => 'الإمام النووي',
                'years' => '631 - 676 H',
                'desc' => 'Bustani ya Waadilifu, kiongozi maarufu cha tabia njema, maadili na ibada za kila siku.',
                'badge' => 'Maadili',
            ],
        ];
    }

    public function render()
    {
        if (! app()->environment('testing') && Hadith::published()->count() < 950) {
            (new \Database\Seeders\HadithSeeder())->run();
        }

        $query = Hadith::published()->with('chapter.book.collection')
            ->when($this->collection !== '', fn ($q) => $q->whereHas('chapter.book.collection', fn ($q) => $q->where('slug', $this->collection)))
            ->when($this->book !== '', fn ($q) => $q->whereHas('chapter', fn ($q) => $q->where('book_id', $this->book)))
            ->when($this->chapter !== '', fn ($q) => $q->where('chapter_id', $this->chapter))
            ->when(trim($this->search) !== '', fn ($q) => $q->search(mb_substr($this->search, 0, 200)));

        $collectionCounts = Hadith::published()
            ->join('chapters', 'hadiths.chapter_id', '=', 'chapters.id')
            ->join('books', 'chapters.book_id', '=', 'books.id')
            ->selectRaw('books.collection_id, count(hadiths.id) as total')
            ->groupBy('books.collection_id')
            ->pluck('total', 'collection_id');

        $collectionPreviews = Hadith::published()
            ->with('chapter.book.collection')
            ->orderBy('id')
            ->get()
            ->groupBy(fn ($h) => $h->chapter->book->collection->slug ?? '')
            ->map(fn ($group) => $group->take(1));

        return view('livewire.library', [
            'collections' => Collection::orderBy('id')->get(),
            'collectionCounts' => $collectionCounts,
            'collectionPreviews' => $collectionPreviews,
            'narratorMeta' => $this->getNarratorMeta(),
            'books' => Book::whereHas('chapters.hadiths', fn ($q) => $q->published())
                ->when($this->collection !== '', fn ($q) => $q->whereHas('collection', fn ($q) => $q->where('slug', $this->collection)))
                ->with('collection')->orderBy('collection_id')->orderBy('number')->get(),
            'chapters' => Chapter::where('book_id', $this->book ?: 0)
                ->whereHas('hadiths', fn ($q) => $q->published())
                ->when($this->collection !== '', fn ($q) => $q->whereHas('book.collection', fn ($q) => $q->where('slug', $this->collection)))
                ->orderBy('number')->get(),
            'hadiths' => $query->orderBy('id')->paginate(10),
            'total' => Hadith::published()->count(),
            'bookCount' => Book::whereHas('chapters.hadiths', fn ($q) => $q->published())->count(),
        ])->layout('components.layouts.app', ['title' => 'Maktaba ya Hadith']);
    }
}
