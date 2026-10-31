<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MemorizationCard extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'ease' => 'float',
            'due_at' => 'datetime',
            'last_reviewed_at' => 'datetime',
        ];
    }

    public function hadith(): BelongsTo
    {
        return $this->belongsTo(Hadith::class);
    }

    /**
     * Ratiba ya kurudia (toleo rahisi la SM-2).
     * $grade: 0 = sikumbuki, 1 = vigumu, 2 = vizuri, 3 = rahisi.
     */
    public function applyGrade(int $grade): void
    {
        $grade = max(0, min(3, $grade));
        $now = now();
        $interval = (int) $this->interval_days;
        $reps = (int) $this->repetitions;
        $ease = (float) $this->ease;

        if ($grade === 0) {
            $reps = 0;
            $interval = 0;
            $ease = max(1.3, $ease - 0.2);
            $due = $now->copy()->addMinutes(10);
        } else {
            if ($grade === 1) {
                $interval = max(1, (int) round(max(1, $interval) * 1.2));
                $ease = max(1.3, $ease - 0.15);
            } elseif ($grade === 2) {
                $interval = $reps === 0 ? 1 : ($reps === 1 ? 3 : max(1, (int) round($interval * $ease)));
            } else {
                $interval = $reps === 0 ? 3 : max(2, (int) round(max(1, $interval) * $ease * 1.3));
                $ease += 0.15;
            }
            $reps++;
            $due = $now->copy()->addDays($interval);
        }

        $this->fill([
            'interval_days' => $interval,
            'repetitions' => $reps,
            'ease' => round($ease, 2),
            'due_at' => $due,
            'last_reviewed_at' => $now,
        ])->save();
    }
}
