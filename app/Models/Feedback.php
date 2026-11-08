<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Feedback extends Model
{
    protected $table = 'feedback';

    protected $fillable = [
        'user_id',
        'category',
        'name',
        'contact',
        'message',
        'page_url',
        'hadith_ref',
        'status',
        'admin_notes',
        'ip_address',
        'user_agent',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function categoryLabel(): string
    {
        return match ($this->category) {
            'feature' => '💡 Wazo Jipya',
            'correction' => '✍️ Marekebisho',
            'bug' => '🐛 Hitilafu',
            default => '💬 Ushauri Mkuu',
        };
    }

    public function categoryBadgeColor(): string
    {
        return match ($this->category) {
            'feature' => 'adm-badge-blue',
            'correction' => 'adm-badge-purple',
            'bug' => 'adm-badge-red',
            default => 'adm-badge-emerald',
        };
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'read' => 'Imesomwa',
            'resolved' => 'Imetatuliwa',
            default => 'Mpya',
        };
    }
}
