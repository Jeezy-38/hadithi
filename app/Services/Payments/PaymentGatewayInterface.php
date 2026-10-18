<?php

namespace App\Services\Payments;

use App\Models\Payment;

interface PaymentGatewayInterface
{
    /**
     * Anzisha agizo la malipo na pata URL ya kuelekezwa mteja.
     *
     * @return array{
     *     status: string,
     *     redirect_url?: string,
     *     tracking_id?: string,
     *     message?: string,
     *     error?: string
     * }
     */
    public function initiateOrder(Payment $payment, array $options = []): array;

    /**
     * Ulizia hadhi ya muamala kutoka kwenye lango la malipo.
     *
     * @return array{
     *     status: string,
     *     amount?: float,
     *     currency?: string,
     *     payment_method?: string,
     *     gateway_reference?: string,
     *     raw_status?: string,
     *     error?: string
     * }
     */
    public function getTransactionStatus(string $orderTrackingId): array;

    /**
     * Sajili anwani ya IPN (Instant Payment Notification).
     */
    public function registerIpn(string $ipnUrl): ?string;
}
