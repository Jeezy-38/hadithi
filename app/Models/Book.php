<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    protected $guarded = [];

    public function collection(): BelongsTo
    {
        return $this->belongsTo(Collection::class);
    }

    public function chapters(): HasMany
    {
        return $this->hasMany(Chapter::class);
    }

    /** Jina la kitabu kwa lugha ya hadith: 'en' = Kiingereza, nyingine = Kiswahili (kama lilivyo). */
    public function titleFor(string $lang): string
    {
        $title = (string) $this->title_sw;
        if ($lang !== 'en') {
            return $title;
        }
        $clean = trim((string) preg_replace('/\s*\(Kiingereza\)\s*$/u', '', $title));

        return (string) (config('book_titles_en')[$clean] ?? $clean);
    }
}
