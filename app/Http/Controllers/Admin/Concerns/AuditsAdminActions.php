<?php

namespace App\Http\Controllers\Admin\Concerns;

use App\Models\AdminLog;
use Illuminate\Database\Eloquent\Model;

trait AuditsAdminActions
{
    /** Hifadhi kumbukumbu ya kitendo cha admin (haivunji kitendo kikishindwa). */
    protected function audit(string $action, string $description, ?Model $target = null): void
    {
        try {
            AdminLog::create([
                'user_id' => auth()->id(),
                'action' => $action,
                'description' => mb_substr($description, 0, 255),
                'target_type' => $target ? class_basename($target) : null,
                'target_id' => $target?->getKey(),
                'ip_address' => request()->ip(),
            ]);
        } catch (\Throwable $e) {
            report($e);
        }
    }
}
