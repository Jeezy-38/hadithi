<?php

namespace App\Livewire;

use App\Models\Dua;
use App\Models\DuaCategory;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class DuaIndex extends Component
{
    use WithPagination;

    #[Url(as: 'q', history: true)]
    public string $search = '';

    #[Url(as: 'kundi', history: true)]
    public string $category = '';

    public function updated($property): void
    {
        if (in_array($property, ['search', 'category'])) {
            $this->resetPage();
        }
    }

    public function selectCategory(string $slug): void
    {
        if ($this->category === $slug) {
            $this->category = '';
        } else {
            $this->category = $slug;
        }
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->reset('search', 'category');
        $this->resetPage();
    }

    public function render()
    {
        $categories = DuaCategory::orderBy('order')->withCount('publishedDuas')->get();

        $query = Dua::published()->with('category')
            ->when($this->category !== '', fn ($q) => $q->whereHas('category', fn ($c) => $c->where('slug', $this->category)))
            ->when(trim($this->search) !== '', fn ($q) => $q->search(mb_substr($this->search, 0, 150)))
            ->orderBy('order')
            ->orderBy('id');

        $duas = $query->paginate(12);

        $selectedCategory = $this->category !== ''
            ? $categories->firstWhere('slug', $this->category)
            : null;

        return view('livewire.dua-index', [
            'categories' => $categories,
            'duas' => $duas,
            'selectedCategory' => $selectedCategory,
            'totalCount' => Dua::published()->count(),
        ])->layout('components.layouts.app', ['title' => 'Dua & Adhkar · Hisn al-Muslim']);
    }
}
