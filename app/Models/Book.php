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

    /** Jina la kitabu: 'sw' (kama lilivyo), 'en', 'es', 'ar'. */
    public function titleFor(string $lang): string
    {
        $title = (string) $this->title_sw;
        if ($lang === 'sw') {
            return $title;
        }
        $clean = \App\Support\TitleI18n::clean($title);
        $en = (string) (config('book_titles_en')[$clean] ?? $clean);
        if ($lang === 'es') {
            return \App\Support\TitleI18n::es($clean) ?? \App\Support\TitleI18n::es($en) ?? $en;
        }
        if ($lang === 'ar') {
            return \App\Support\TitleI18n::hasArabic($this->title_ar) ? trim((string) $this->title_ar) : $en;
        }

        return $en;
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
