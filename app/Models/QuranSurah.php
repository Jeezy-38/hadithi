<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuranSurah extends Model
{
    protected $table = 'quran_surahs';

    protected $fillable = [
        'number',
        'name_ar',
        'name_en',
        'name_sw',
        'translation_sw',
        'revelation_type',
        'total_verses',
        'juz_start',
        'page_start',
        'bismillah_pre',
    ];

    protected $casts = [
        'number' => 'integer',
        'total_verses' => 'integer',
        'juz_start' => 'integer',
        'page_start' => 'integer',
        'bismillah_pre' => 'boolean',
    ];

    public function ayahs(): HasMany
    {
        return $this->hasMany(QuranAyah::class, 'surah_number', 'number')->orderBy('verse_number');
    }
}
