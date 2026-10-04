<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuranAyah extends Model
{
    protected $table = 'quran_ayahs';

    protected $fillable = [
        'surah_number',
        'verse_number',
        'juz_number',
        'page_number',
        'arabic_text',
        'arabic_clean',
        'translation_sw',
        'translation_en',
        'audio_url',
    ];

    protected $casts = [
        'surah_number' => 'integer',
        'verse_number' => 'integer',
        'juz_number' => 'integer',
        'page_number' => 'integer',
    ];

    public function surah(): BelongsTo
    {
        return $this->belongsTo(QuranSurah::class, 'surah_number', 'number');
    }

    /**
     * Ensure Bismillah is separated from Ayah 1 for Surahs 2 to 114.
     */
    public function getArabicTextAttribute(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        if ((int) $this->surah_number > 1 && (int) $this->verse_number === 1) {
            return self::stripBismillahPrefix($value);
        }

        return $value;
    }

    /**
     * Strip leading Bismillah prefix from Arabic text if present.
     */
    public static function stripBismillahPrefix(string $text): string
    {
        $pattern = '/^(\x{FEFF}|\s)*(بِ?سْمِ?\s*[\x{0600}-\x{06FF}\s]+?(?:ٱلرَّحِيمِ|الرَّحِيمِ|الرَّحِيم|ٱلرَّحِيم))\s*/u';

        return preg_replace($pattern, '', $text) ?? $text;
    }
}
