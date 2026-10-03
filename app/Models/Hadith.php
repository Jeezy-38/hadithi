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
        return ['is_published' => 'boolean', 'reviewed_at' => 'date', 'source_fetched_at' => 'datetime'];
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

    public function bookmarkedBy(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, 'bookmarks')->withTimestamps();
    }

    public function scopePublished(Builder $query): void
    {
        $query->where('is_published', true);
    }

    public function scopeSearch(Builder $query, string $term): void
    {
        // Escape LIKE wildcards so a user's % or _ is treated literally.
        $term = '%'.str_replace(['!', '%', '_'], ['!!', '!%', '!_'], self::normalize($term)).'%';
        $query->where(function (Builder $query) use ($term) {
            foreach (['search_arabic', 'search_swahili', 'english', 'number', 'reference'] as $column) {
                $query->orWhereRaw("LOWER($column) LIKE ? ESCAPE '!'", [$term]);
            }
        });
    }
}
