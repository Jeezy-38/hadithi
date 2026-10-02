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

    public function render()
    {
        $query = Hadith::published()->with('chapter.book.collection')
            ->when($this->collection !== '', fn ($q) => $q->whereHas('chapter.book.collection', fn ($q) => $q->where('slug', $this->collection)))
            ->when($this->book !== '', fn ($q) => $q->whereHas('chapter', fn ($q) => $q->where('book_id', $this->book)))
            ->when($this->chapter !== '', fn ($q) => $q->where('chapter_id', $this->chapter))
            ->when(trim($this->search) !== '', fn ($q) => $q->search(mb_substr($this->search, 0, 200)));

        return view('livewire.library', [
            'collections' => Collection::orderBy('id')->get(),
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
