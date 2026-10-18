<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reference',
        'donor_name',
        'donor_email',
        'donor_phone',
        'amount',
        'currency',
        'purpose',
        'tier',
        'billing_cycle',
        'payment_method',
        'gateway',
        'gateway_reference',
        'status',
        'is_anonymous',
        'message',
        'metadata',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'is_anonymous' => 'boolean',
            'metadata' => 'array',
            'completed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopePublicDonations($query)
    {
        return $query->where('status', 'completed')
            ->where('is_anonymous', false);
    }

    public function scopeRecent($query)
    {
        return $query->orderByDesc('completed_at')->orderByDesc('id');
    }

    /**
     * Mark this payment as completed and grant supporter status to associated user if applicable.
     */
    public function markAsCompleted(?string $gatewayReference = null, array $metadata = []): void
    {
        $this->status = 'completed';
        $this->completed_at = now();

        if ($gatewayReference) {
            $this->gateway_reference = $gatewayReference;
        }

        if (! empty($metadata)) {
            $existing = $this->metadata ?? [];
            $this->metadata = array_merge($existing, $metadata);
        }

        $this->save();

        if ($this->user_id && $this->user) {
            $user = $this->user;
            $user->is_supporter = true;
            $user->supporter_tier = $this->tier ?? 'supporter';
            $user->supporter_since = $user->supporter_since ?? now();

            if ($this->billing_cycle === 'monthly') {
                $user->supporter_expires_at = now()->addDays(31);
            } else {
                // One-time donation supporter status remains active for at least 1 year
                $user->supporter_expires_at = now()->addYear();
            }

            $user->save();
        }
    }

    /**
     * Human-friendly formatted amount.
     */
    public function formattedAmount(): string
    {
        $num = number_format((float) $this->amount, $this->currency === 'TZS' ? 0 : 2);

        return $this->currency === 'TZS' ? "TZS {$num}" : "{$this->currency} {$num}";
    }

    /**
     * Swahili label for payment purpose.
     */
    public function purposeLabel(): string
    {
        return match ($this->purpose) {
            'sadaka_jariya' => 'Sadaka Jariya',
            'supporter_membership' => 'Msaidizi wa App',
            'general_donation' => 'Mchango wa Jumla',
            default => 'Sadaka',
        };
    }
}
