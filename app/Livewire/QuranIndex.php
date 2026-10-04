<?php

namespace App\Livewire;

use App\Models\QuranSurah;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Livewire\Attributes\Url;
use Livewire\Component;

class QuranIndex extends Component
{
    #[Url(as: 'q', history: true)]
    public string $search = '';

    #[Url(as: 'aina', history: true)]
    public string $type = 'all'; // 'all', 'Makki', 'Madani'

    #[Url(as: 'juz', history: true)]
    public string $juz = '';

    public function setType(string $type): void
    {
        $this->type = in_array($type, ['all', 'Makki', 'Madani']) ? $type : 'all';
    }

    public function setJuz(string $juz): void
    {
        $this->juz = $this->juz === $juz ? '' : $juz;
    }

    public function clearFilters(): void
    {
        $this->reset('search', 'type', 'juz');
    }

    public function render()
    {
        if (! Schema::hasTable('quran_surahs')) {
            $path = database_path('data/quran/surahs.json');
            $raw = file_exists($path) ? json_decode(file_get_contents($path), true) : [];
            $allSurahs = collect($raw)->map(fn ($item) => (object) $item);
        } else {
            $allSurahs = QuranSurah::orderBy('number')->get();
        }

        $filtered = $allSurahs->filter(function ($surah) {
            // Type filter
            if ($this->type !== 'all' && strtolower($surah->revelation_type) !== strtolower($this->type)) {
                return false;
            }

            // Juz filter
            if ($this->juz !== '' && (int) $surah->juz_start !== (int) $this->juz) {
                return false;
            }

            // Search filter
            if ($this->search !== '') {
                $term = mb_strtolower(trim($this->search));
                $numMatch = (string) $surah->number === $term;
                $swMatch = str_contains(mb_strtolower($surah->name_sw), $term);
                $transMatch = str_contains(mb_strtolower($surah->translation_sw), $term);
                $enMatch = str_contains(mb_strtolower($surah->name_en), $term);
                $arMatch = str_contains(mb_strtolower($surah->name_ar), $term);

                if (! ($numMatch || $swMatch || $transMatch || $enMatch || $arMatch)) {
                    return false;
                }
            }

            return true;
        });

        $totalSurahs = $allSurahs->count();
        $makkiCount = $allSurahs->where('revelation_type', 'Makki')->count();
        $madaniCount = $allSurahs->where('revelation_type', 'Madani')->count();

        return view('livewire.quran-index', [
            'surahs' => $filtered,
            'totalSurahs' => $totalSurahs,
            'makkiCount' => $makkiCount,
            'madaniCount' => $madaniCount,
        ])->layout('components.layouts.app', ['title' => "Qur'ani Tukufu · Sura 114 na Tafsiri ya Kiswahili"]);
    }
}
