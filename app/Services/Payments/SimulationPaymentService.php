<?php

namespace App\Services\Payments;

use App\Models\Payment;
use Illuminate\Support\Str;

class SimulationPaymentService implements PaymentGatewayInterface
{
    /**
     * Anzisha agizo la majaribio na peleka mtumiaji kwenye ukurasa wa majaribio wa malipo.
     */
    public function initiateOrder(Payment $payment, array $options = []): array
    {
        $trackingId = 'SIM-' . strtoupper(Str::random(12));

        $payment->update([
            'gateway_reference' => $trackingId,
            'metadata' => array_merge($payment->metadata ?? [], [
                'simulation_mode' => true,
                'initiated_at' => now()->toIso8601String(),
            ]),
        ]);

        return [
            'status' => 'success',
            'tracking_id' => $trackingId,
            'redirect_url' => route('payments.simulate', ['reference' => $payment->reference]),
        ];
    }

    /**
     * Pata hadhi ya agizo la majaribio.
     */
    public function getTransactionStatus(string $orderTrackingId): array
    {
        $payment = Payment::where('gateway_reference', $orderTrackingId)->first();

        if (! $payment) {
            return [
                'status' => 'failed',
                'message' => 'Muamala wa majaribio haukupatikana.',
            ];
        }

        return [
            'status' => $payment->status === 'completed' ? 'completed' : 'pending',
            'amount' => (float) $payment->amount,
            'currency' => $payment->currency,
            'payment_method' => $payment->payment_method ?? 'mobile_money',
            'gateway_reference' => $orderTrackingId,
            'raw_status' => ucfirst($payment->status),
            'confirmation_code' => 'TEST-' . strtoupper(Str::random(8)),
        ];
    }

    /**
     * IPN ya majaribio haihitaji usajili wa nje.
     */
    public function registerIpn(string $ipnUrl): ?string
    {
        return 'SIM-IPN-' . strtoupper(Str::random(8));
    }
}
