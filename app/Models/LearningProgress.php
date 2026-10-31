<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LearningProgress extends Model
{
    protected $table = 'learning_progress';

    protected $guarded = [];

    protected function casts(): array
    {
        return ['completed_at' => 'datetime'];
    }
}
