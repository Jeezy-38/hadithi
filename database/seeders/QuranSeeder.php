<?php

namespace Database\Seeders;

use App\Models\QuranAyah;
use App\Models\QuranSurah;
use Illuminate\Database\Seeder;

class QuranSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed all 114 Surahs
        $surahsPath = database_path('data/quran/surahs.json');
        if (file_exists($surahsPath)) {
            $surahs = json_decode(file_get_contents($surahsPath), true) ?: [];
            foreach ($surahs as $s) {
                QuranSurah::updateOrCreate(
                    ['number' => $s['number']],
                    [
                        'name_ar' => $s['name_ar'],
                        'name_en' => $s['name_en'],
                        'name_sw' => $s['name_sw'],
                        'translation_sw' => $s['translation_sw'],
                        'revelation_type' => $s['revelation_type'],
                        'total_verses' => $s['total_verses'],
                        'juz_start' => $s['juz_start'],
                        'page_start' => $s['page_start'],
                        'bismillah_pre' => $s['bismillah_pre'] ?? true,
                    ]
                );
            }
        }

        // 2. Seed Ayahs (from complete dataset if present, or starter fallback)
        $completePath = database_path('data/quran/complete_ayahs.json');
        $ayahsPath = file_exists($completePath) ? $completePath : database_path('data/quran/starter_ayahs.json');
        if (file_exists($ayahsPath)) {
            $ayahs = json_decode(file_get_contents($ayahsPath), true) ?: [];
            $records = [];
            foreach ($ayahs as $a) {
                $records[] = [
                    'surah_number' => $a['surah_number'],
                    'verse_number' => $a['verse_number'],
                    'juz_number' => $a['juz_number'] ?? 1,
                    'page_number' => $a['page_number'] ?? 1,
                    'arabic_text' => $a['arabic_text'],
                    'translation_sw' => $a['translation_sw'],
                    'translation_en' => $a['translation_en'] ?? null,
                    'audio_url' => $a['audio_url'] ?? null,
                ];
            }

            foreach (array_chunk($records, 500) as $chunk) {
                QuranAyah::upsert(
                    $chunk,
                    ['surah_number', 'verse_number'],
                    ['juz_number', 'page_number', 'arabic_text', 'translation_sw', 'translation_en', 'audio_url']
                );
            }
        }
    }
}
