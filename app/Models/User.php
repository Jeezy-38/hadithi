<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'is_supporter',
        'supporter_tier',
        'supporter_since',
        'supporter_expires_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_supporter' => 'boolean',
            'supporter_since' => 'datetime',
            'supporter_expires_at' => 'datetime',
        ];
    }

    public function socialAccounts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SocialAccount::class);
    }

    public function bookmarks(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Hadith::class, 'bookmarks')->withTimestamps();
    }

    public function payments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Check if user is an active supporter.
     */
    public function isSupporter(): bool
    {
        if (! $this->is_supporter) {
            return false;
        }

        if ($this->supporter_expires_at && $this->supporter_expires_at->isPast()) {
            return false;
        }

        return true;
    }

    /**
     * Swahili badge title for supporter.
     */
    public function supporterBadge(): ?string
    {
        if (! $this->isSupporter()) {
            return null;
        }

        return match ($this->supporter_tier) {
            'gold' => 'Msaidizi Mkuu (Dhahabu)',
            'silver' => 'Msaidizi Mwaminifu (Fedha)',
            default => 'Msaidizi wa App',
        };
    }
}
