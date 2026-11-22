<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Hadith extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'reviewed_at' => 'date',
            'source_fetched_at' => 'datetime',
            'hints' => 'array',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Hadith $hadith) {
            $hadith->search_arabic = self::normalize($hadith->arabic);
            $hadith->search_swahili = self::normalize($hadith->swahili);
        });
    }

    public static function normalize(string $text): string
    {
        $text = preg_replace('/[\x{0610}-\x{061A}\x{064B}-\x{065F}\x{0670}\x{06D6}-\x{06ED}\x{0640}]/u', '', $text);

        return mb_strtolower(strtr(trim($text), ['أ' => 'ا', 'إ' => 'ا', 'آ' => 'ا', 'ٱ' => 'ا', 'ى' => 'ي']));
    }

    public function chapter(): BelongsTo
    {
        return $this->belongsTo(Chapter::class);
    }

    public function getStudioAudioUrl(): ?string
    {
        if (! empty($this->audio_url)) {
            return $this->audio_url;
        }

        // Studio audio mapping for collections with authentic audio recordings
        $colSlug = $this->chapter?->book?->collection?->slug ?? '';
        if ($colSlug === 'nawawi40') {
            $num = (int) preg_replace('/\D/', '', (string) $this->number);
            if ($num >= 1 && $num <= 42) {
                return sprintf('https://www.kalamullah.com/An-Nawawis%%20Forty%%20Hadith/nawawi%02d.mp3', $num);
            }
        }

        return null;
    }

    public function bookmarkedBy(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, 'bookmarks')->withTimestamps();
    }

    /** Hadith zilizochapishwa NA zenye tafsiri ya Kiswahili (kwa moduli zote za kujifunza, hadith ya leo, nk.). */
    public function scopePublished(Builder $query): void
    {
        $query->where('is_published', true)->where('swahili', '!=', '');
    }

    /** Hadith zote zilizochapishwa, pamoja na zile za Kiarabu + Kiingereza tu (Maktaba na ukurasa wa hadith). */
    public function scopeVisible(Builder $query): void
    {
        $query->where('is_published', true);
    }

    /** Maandishi ya kuonyesha: Kiswahili, au Kiingereza kama Kiswahili hakipo. */
    public function getDisplayTextAttribute(): string
    {
        return (string) (($this->swahili ?? '') !== '' ? $this->swahili : $this->english);
    }

    public function getHasSwahiliAttribute(): bool
    {
        return ($this->swahili ?? '') !== '';
    }

    public function scopeSearch(Builder $query, string $term): void
    {
        $normalized = self::normalize($term);
        // Escape LIKE wildcards so a user's % or _ is treated literally.
        $like = '%'.str_replace(['!', '%', '_'], ['!!', '!%', '!_'], $normalized).'%';

        $driver = $query->getConnection()->getDriverName();
        $tokens = [];
        if (in_array($driver, ['mysql', 'mariadb'], true)) {
            preg_match_all('/[\p{L}\p{N}]{3,}/u', $normalized, $matches);
            $tokens = array_slice(array_values(array_unique($matches[0] ?? [])), 0, 8);
        }

        $query->where(function (Builder $query) use ($like, $tokens) {
            if ($tokens !== []) {
                $booleanQuery = implode(' ', array_map(fn (string $t) => '+'.$t.'*', $tokens));
                $query->whereRaw(
                    'MATCH(search_arabic, search_swahili, english) AGAINST(? IN BOOLEAN MODE)',
                    [$booleanQuery]
                );
                foreach (['number', 'reference'] as $column) {
                    $query->orWhereRaw("LOWER($column) LIKE ? ESCAPE '!'", [$like]);
                }

                return;
            }

            foreach (['search_arabic', 'search_swahili', 'english', 'number', 'reference'] as $column) {
                $query->orWhereRaw("LOWER($column) LIKE ? ESCAPE '!'", [$like]);
            }
        });
    }
}
