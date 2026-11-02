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
    public function scopeActiveFor(Builder $query, string $slot = 'today'): void
    {
        $today = now()->toDateString();
        $query->where('slot', $slot)
            ->where('is_active', true)
            ->where(fn ($q) => $q->whereNull('starts_at')->orWhere('starts_at', '<=', $today))
            ->where(fn ($q) => $q->whereNull('ends_at')->orWhere('ends_at', '>=', $today));
    }

    /** Mdhamini wa leo: ikiwa wako wengi, wanazunguka kwa siku (haki kwa wote). */
    public static function current(string $slot = 'today'): ?self
    {
        $list = static::activeFor($slot)->orderBy('id')->get();
        if ($list->isEmpty()) {
            return null;
        }

        return $list[(int) now()->format('z') % $list->count()];
    }

    public function getInitialAttribute(): string
    {
        return mb_strtoupper(mb_substr($this->name, 0, 1));
    }
}
