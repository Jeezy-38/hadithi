<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Dua extends Model
{
    protected $fillable = [
        'category_id',
        'title_sw',
        'title_en',
        'title_ar',
        'arabic',
        'transliteration',
        'swahili',
        'english',
        'reference',
        'virtue_sw',
        'virtue_en',
        'target_count',
        'order',
        'is_published',
    ];

    protected $casts = [
        'target_count' => 'integer',
        'order' => 'integer',
        'is_published' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(DuaCategory::class, 'category_id');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    public function scopeSearch(Builder $query, string $term): Builder
    {
        $term = trim($term);
        if ($term === '') {
            return $query;
        }

        return $query->where(function (Builder $q) use ($term) {
            $q->where('title_sw', 'like', "%{$term}%")
                ->orWhere('title_en', 'like', "%{$term}%")
                ->orWhere('title_ar', 'like', "%{$term}%")
                ->orWhere('swahili', 'like', "%{$term}%")
                ->orWhere('arabic', 'like', "%{$term}%")
                ->orWhere('transliteration', 'like', "%{$term}%")
                ->orWhere('reference', 'like', "%{$term}%");
        });
    }
}
