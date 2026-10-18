<?php

namespace App\Services\Payments;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentFulfillmentService
{
    /**
     * Kamilisha muamala wa malipo na kumpa mtumiaji hadhi ya Supporter.
     */
    public function fulfill(Payment $payment, array $details = []): Payment
    {
        return DB::transaction(function () use ($payment, $details) {
            // Ikiwa tayari umekamilika, usirudie kumpa pointi au jumla mara mbili
            $alreadyCompleted = $payment->status === 'completed';

            $channel = $details['payment_method'] ?? $payment->payment_method ?? 'mobile_money';
            $gatewayRef = $details['gateway_reference'] ?? $payment->gateway_reference;
            $confirmationCode = $details['confirmation_code'] ?? null;

            $meta = $payment->metadata ?? [];
            if ($confirmationCode) {
                $meta['confirmation_code'] = $confirmationCode;
            }
            if (! empty($details['raw_status'])) {
                $meta['gateway_raw_status'] = $details['raw_status'];
            }

            $payment->update([
                'status' => 'completed',
                'completed_at' => $payment->completed_at ?: now(),
                'payment_method' => $channel,
                'gateway_reference' => $gatewayRef,
                'metadata' => $meta,
            ]);

            if ($alreadyCompleted) {
                return $payment;
            }

            // Tafuta mtumiaji: ama kupitia user_id au email
            $user = null;
            if ($payment->user_id) {
                $user = User::find($payment->user_id);
            } elseif (! empty($payment->donor_email)) {
                $user = User::where('email', strtolower(trim((string) $payment->donor_email)))->first();
                if ($user) {
                    $payment->update(['user_id' => $user->id]);
                }
            }

            if ($user) {
                $this->upgradeUserSupporter($user, $payment);
            }

            Log::info("Malipo yamekamilika kikamilifu: {$payment->reference} - Kiasi: {$payment->currency} {$payment->amount}");

            return $payment;
        });
    }

    /**
     * Sasisha hadhi ya uanachama wa Supporter kwa mtumiaji.
     */
    protected function upgradeUserSupporter(User $user, Payment $payment): void
    {
        $user->is_supporter = true;

        if (empty($user->supporter_since)) {
            $user->supporter_since = now();
        }

        // Hesabu beji: gold > silver > supporter
        $currentTier = $user->supporter_tier;
        $newTier = $payment->tier ?: 'supporter';

        $tierPriority = [
            'gold' => 3,
            'silver' => 2,
            'supporter' => 1,
        ];

        $currentScore = $tierPriority[$currentTier] ?? 0;
        $newScore = $tierPriority[$newTier] ?? 1;

        if ($newScore >= $currentScore) {
            $user->supporter_tier = $newTier;
        }

        $user->save();
    }
}
