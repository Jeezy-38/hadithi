<?php

namespace App\Support;

use Illuminate\Support\HtmlString;

/** Vichwa vya vitabu/mada kwa lugha 4 (sw, en, ar, es) kwa kutumia config/titles_i18n.php. */
class TitleI18n
{
    public static function norm(string $s): string
    {
        return trim((string) preg_replace('/\s+/u', ' ', $s));
    }

    /** Ondoa kiambishi "(Kiingereza)" kilichowekwa kwenye vitabu vya Kiingereza. */
    public static function clean(string $s): string
    {
        return self::norm((string) preg_replace('/\s*\(Kiingereza\)\s*$/u', '', $s));
    }

    public static function es(string $key): ?string
    {
        return config('titles_i18n.es')[self::norm($key)] ?? null;
    }

    public static function chapterEn(string $key): ?string
    {
        return config('titles_i18n.chapters_en')[self::norm($key)] ?? null;
    }

    public static function hasArabic(?string $s): bool
    {
        return $s !== null && preg_match('/\p{Arabic}/u', $s) === 1;
    }

    /** Tengeneza spans zisizojirudia; CSS huchagua kwa html[data-title-lang]. */
    public static function html(array $byLang): HtmlString
    {
        $groups = [];
        foreach (['sw', 'en', 'ar', 'es'] as $l) {
            $t = (string) ($byLang[$l] ?? $byLang['en'] ?? $byLang['sw'] ?? '');
            $groups[$t][] = $l;
        }
        $out = '';
        foreach ($groups as $text => $langs) {
            $attr = in_array('ar', $langs, true) ? ' lang="ar"' : '';
            $out .= '<span data-tl="'.implode(' ', $langs).'"'.$attr.'>'.e($text).'</span>';
        }

        return new HtmlString($out);
    }

    /** Kichwa cha kitabu (kwa lugha 4) kutoka kwa majina ghafi (kwa safu zisizo Eloquent). */
    public static function bookHtml(?string $titleSw, ?string $titleAr = null): HtmlString
    {
        $b = new \App\Models\Book(['title_sw' => (string) $titleSw, 'title_ar' => $titleAr]);

        return $b->titleHtml();
    }
}
