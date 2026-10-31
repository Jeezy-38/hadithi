<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LearningDay extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return ['day' => 'date'];
    }

    /** Rekodi kuwa mtumiaji amejifunza leo (saa za Dar es Salaam). */
    public static function touchToday(int $userId): void
    {
        static::firstOrCreate([
            'user_id' => $userId,
            'day' => now('Africa/Dar_es_Salaam')->toDateString(),
        ]);
    }

    /** Idadi ya siku mfululizo, ikiishia leo au jana. */
    public static function streakFor(int $userId): int
    {
        $days = static::where('user_id', $userId)->orderByDesc('day')->limit(400)->pluck('day')
            ->map(fn ($d) => $d->toDateString())->all();
        if (! $days) {
            return 0;
        }
        $cursor = now('Africa/Dar_es_Salaam')->startOfDay();
        if ($days[0] !== $cursor->toDateString()) {
            $cursor = $cursor->subDay();
            if ($days[0] !== $cursor->toDateString()) {
                return 0;
            }
        }
        $streak = 0;
        foreach ($days as $d) {
            if ($d === $cursor->toDateString()) {
                $streak++;
                $cursor = $cursor->subDay();
            } elseif ($d < $cursor->toDateString()) {
                break;
            }
        }

        return $streak;
    }
}
