<?php

namespace App\Services;

use App\Models\QuranAyah;
use App\Models\QuranSurah;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class QuranService
{
    /**
     * Get all 114 Surahs with fallback to local JSON if database not yet migrated.
     */
    public function getAllSurahs(): Collection
    {
        if (Schema::hasTable('quran_surahs')) {
            $surahs = QuranSurah::orderBy('number')->get();
            if ($surahs->isNotEmpty()) {
                return $surahs;
            }
        }

        // Fallback to local JSON
        $path = database_path('data/quran/surahs.json');
        if (file_exists($path)) {
            $data = json_decode(file_get_contents($path), true) ?: [];
            return collect($data)->map(function ($item) {
                return (object) $item;
            });
        }

        return collect();
    }

    /**
     * Get a specific Surah by number, including its Ayahs.
     */
    public function getSurah(int $number): ?QuranSurah
    {
        if ($number < 1 || $number > 114) {
            return null;
        }

        if (! Schema::hasTable('quran_surahs')) {
            return null;
        }

        $surah = QuranSurah::where('number', $number)->first();
        if (! $surah) {
            return null;
        }

        // Check if ayahs are present
        if ($surah->ayahs()->count() === 0) {
            // Attempt to load from starter ayahs or fetch from API
            $this->loadStarterAyahsForSurah($number);
            
            // If still empty, try on-demand fetch
            if ($surah->ayahs()->count() === 0) {
                $this->fetchAndSaveSurahAyahs($number);
            }
        }

        return $surah->load(['ayahs' => function ($q) {
            $q->orderBy('verse_number');
        }]);
    }

    /**
     * Get adjacent Surahs for navigation.
     */
    public function getNavigation(int $number): array
    {
        $prev = null;
        $next = null;

        if (Schema::hasTable('quran_surahs')) {
            if ($number > 1) {
                $prev = QuranSurah::where('number', $number - 1)->first();
            }
            if ($number < 114) {
                $next = QuranSurah::where('number', $number + 1)->first();
            }
        }

        return [
            'prev' => $prev,
            'next' => $next,
        ];
    }

    /**
     * Load ayahs from local complete dataset or starter file if present.
     */
    public function loadStarterAyahsForSurah(int $number): bool
    {
        $completePath = database_path('data/quran/complete_ayahs.json');
        $path = file_exists($completePath) ? $completePath : database_path('data/quran/starter_ayahs.json');
        if (! file_exists($path)) {
            return false;
        }

        $allAyahs = json_decode(file_get_contents($path), true) ?: [];
        $ayahsForSurah = array_values(array_filter($allAyahs, fn ($ay) => ($ay['surah_number'] ?? null) === $number));

        if (empty($ayahsForSurah)) {
            return false;
        }

        $records = [];
        foreach ($ayahsForSurah as $ayData) {
            $records[] = [
                'surah_number' => $ayData['surah_number'],
                'verse_number' => $ayData['verse_number'],
                'juz_number' => $ayData['juz_number'] ?? 1,
                'page_number' => $ayData['page_number'] ?? 1,
                'arabic_text' => $ayData['arabic_text'],
                'translation_sw' => $ayData['translation_sw'],
                'translation_en' => $ayData['translation_en'] ?? null,
                'audio_url' => $ayData['audio_url'] ?? null,
            ];
        }

        QuranAyah::upsert(
            $records,
            ['surah_number', 'verse_number'],
            ['juz_number', 'page_number', 'arabic_text', 'translation_sw', 'translation_en', 'audio_url']
        );

        return true;
    }

    /**
     * Fetch ayahs for a given Surah from Al-Quran Cloud API and cache locally in DB.
     */
    public function fetchAndSaveSurahAyahs(int $number): bool
    {
        try {
            $url = "https://api.alquran.cloud/v1/surah/{$number}/editions/quran-uthmani,sw.barwani,en.sahih";
            $response = Http::timeout(12)->get($url);

            if (! $response->successful()) {
                return false;
            }

            $json = $response->json();
            $data = $json['data'] ?? [];
            if (count($data) < 2) {
                return false;
            }

            $arAyahs = $data[0]['ayahs'] ?? [];
            $swAyahs = $data[1]['ayahs'] ?? [];
            $enAyahs = $data[2]['ayahs'] ?? [];

            foreach ($arAyahs as $index => $arAyah) {
                $verseNum = (int) ($arAyah['numberInSurah'] ?? ($index + 1));
                $swText = $swAyahs[$index]['text'] ?? '';
                $enText = $enAyahs[$index]['text'] ?? '';
                $arText = $arAyah['text'] ?? '';

                if ($number > 1 && $verseNum === 1) {
                    $arText = QuranAyah::stripBismillahPrefix($arText);
                }

                $audioCode = sprintf('%03d%03d', $number, $verseNum);
                $audioUrl = "https://everyayah.com/data/Alafasy_128kbps/{$audioCode}.mp3";

                QuranAyah::updateOrCreate(
                    [
                        'surah_number' => $number,
                        'verse_number' => $verseNum,
                    ],
                    [
                        'juz_number' => (int) ($arAyah['juz'] ?? 1),
                        'page_number' => (int) ($arAyah['page'] ?? 1),
                        'arabic_text' => $arText,
                        'translation_sw' => $swText,
                        'translation_en' => $enText,
                        'audio_url' => $audioUrl,
                    ]
                );
            }

            return true;
        } catch (\Throwable $e) {
            Log::warning("Could not fetch Quran Surah {$number} from API: " . $e->getMessage());
            return false;
        }
    }
}
