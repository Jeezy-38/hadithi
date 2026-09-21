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
}
