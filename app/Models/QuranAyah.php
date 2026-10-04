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
}
