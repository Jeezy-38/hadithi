<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DuaCategory extends Model
{
    protected $fillable = [
        'slug',
        'name_sw',
        'name_en',
        'name_ar',
        'icon',
        'order',
    ];

    public function duas(): HasMany
    {
        return $this->hasMany(Dua::class, 'category_id')->orderBy('order')->orderBy('id');
    }

    public function publishedDuas(): HasMany
    {
        return $this->duas()->where('is_published', true);
    }
}
