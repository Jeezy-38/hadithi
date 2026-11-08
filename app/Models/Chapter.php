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

    /** Kichwa cha mlango: 'sw' (kama kilivyo), 'en', 'es', 'ar'. */
    public function titleFor(string $lang): string
    {
        $T = \App\Support\TitleI18n::class;
        $title = $T::norm((string) $this->title_sw);
        if ($lang === 'sw') {
            return $title;
        }
        $ar = trim((string) $this->title_ar);
        if ($lang === 'ar' && $T::hasArabic($ar)) {
            return $ar;
        }
        $bookEn = function (string $t) use ($T): string {
            $clean = $T::clean($t);

            return (string) (config('book_titles_en')[$clean] ?? $clean);
        };
        $label = ['en' => ['Hadith', 'Chapter'], 'ar' => ['Hadith', 'Chapter'], 'es' => ['Hadiz', 'Capítulo']][$lang] ?? ['Hadith', 'Chapter'];
        if (preg_match('/^(Hadithi ya|Mlango wa) (\d+):\s*(.+)$/u', $title, $m)) {
            $kind = $m[1] === 'Hadithi ya' ? 0 : 1;
            $rest = $T::clean($m[3]);
            $name = $lang === 'es' ? ($T::es($rest) ?? $T::es($bookEn($rest)) ?? $bookEn($rest)) : $bookEn($rest);

            return $label[$kind].' '.$m[2].': '.$name;
        }
        $en = $T::chapterEn($title) ?? (preg_match('/^[\x20-\x7E`\x{2019}]+$/u', $title) && ! preg_match('/\b(wa|ya|za|cha|kwa|na)\b/u', $title) ? $title : null);
        if ($lang === 'es') {
            return $T::es($title) ?? $en ?? ($T::hasArabic($ar) ? $ar : $title);
        }
        if ($en !== null) {
            return $en;
        }

        return $T::hasArabic($ar) ? $ar : $title;
    }

    /** Spans za lugha 4; CSS huchagua kulingana na html[data-title-lang]. */
    public function titleHtml(): \Illuminate\Support\HtmlString
    {
        return \App\Support\TitleI18n::html([
            'sw' => $this->titleFor('sw'), 'en' => $this->titleFor('en'),
            'ar' => $this->titleFor('ar'), 'es' => $this->titleFor('es'),
        ]);
    }
}
