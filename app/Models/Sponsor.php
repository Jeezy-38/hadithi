<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Sponsor extends Model
{
    protected $fillable = ['name', 'tagline', 'url', 'logo_url', 'slot', 'starts_at', 'ends_at', 'is_active', 'clicks'];

    protected $casts = [
        'starts_at' => 'date',
        'ends_at' => 'date',
        'is_active' => 'boolean',
    ];

    /** Wadhamini wanaoonekana leo kwenye nafasi husika. */
    public function scopeActiveFor(Builder $query, string|array $slot = 'today'): void
    {
        $today = now()->toDateString();
        $query->whereIn('slot', (array) $slot)
            ->where('is_active', true)
            ->where(fn ($q) => $q->whereNull('starts_at')->orWhere('starts_at', '<=', $today))
            ->where(fn ($q) => $q->whereNull('ends_at')->orWhere('ends_at', '>=', $today));
    }

    public const SLOTS = [
        'today' => 'Hadithi ya Leo',
        'main' => 'Mdhamini Mkuu (Hadithi ya Leo, mwanzo na kurasa za hadith)',
    ];

    /** Chagua mmoja wa orodha; wakiwa wengi wanazunguka kwa siku (haki kwa wote). */
    private static function rotate($list): ?self
    {
        return $list->isEmpty() ? null : $list[(int) now()->format('z') % $list->count()];
    }

    /** Mdhamini wa nafasi ya «Hadithi ya Leo»: Mkuu ana kipaumbele, kisha wa kawaida. */
    public static function current(string $slot = 'today'): ?self
    {
        if ($slot === 'today') {
            return static::main() ?? static::rotate(static::activeFor('today')->orderBy('id')->get());
        }

        return static::rotate(static::activeFor($slot)->orderBy('id')->get());
    }

    /** Mdhamini Mkuu (ukurasa wa mwanzo, kurasa za hadith). */
    public static function main(): ?self
    {
        return static::rotate(static::activeFor('main')->orderBy('id')->get());
    }

    public function getInitialAttribute(): string
    {
        return mb_strtoupper(mb_substr($this->name, 0, 1));
    }
}
