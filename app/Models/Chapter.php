<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Chapter extends Model
{
    protected $guarded = [];

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function hadiths(): HasMany
    {
        return $this->hasMany(Hadith::class);
    }

    /** Kichwa cha mlango kwa lugha ya hadith ('sw' au 'en'). */
    public function titleFor(string $lang): string
    {
        $title = trim((string) $this->title_sw);
        if ($lang !== 'en') {
            return $title;
        }
        $bookEn = function (string $t): string {
            $clean = trim((string) preg_replace('/\s*\(Kiingereza\)\s*$/u', '', $t));

            return (string) (config('book_titles_en')[$clean] ?? $clean);
        };
        if (preg_match('/^Hadithi ya (\d+):\s*(.+)$/u', $title, $m)) {
            return 'Hadith '.$m[1].': '.$bookEn($m[2]);
        }
        if (preg_match('/^Mlango wa (\d+):\s*(.+)$/u', $title, $m)) {
            return 'Chapter '.$m[1].': '.$bookEn($m[2]);
        }
        if (preg_match('/^[\x20-\x7E]+$/', $title) && ! preg_match('/\b(wa|ya|za|cha|kwa|na)\b/u', $title)) {
            return $title; // tayari ni Kiingereza (mf. "Zakat")
        }
        $ar = trim((string) $this->title_ar);

        return $ar !== '' ? $ar : $title;
    }

    /** Vichwa viwili vinavyobadilika kwa lugha ya kusoma. */
    public function titleHtml(): \Illuminate\Support\HtmlString
    {
        return new \Illuminate\Support\HtmlString(
            '<span data-reading="sw">'.e($this->titleFor('sw')).'</span><span data-reading="en">'.e($this->titleFor('en')).'</span>'
        );
    }
}
