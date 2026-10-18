<?php

namespace App\Services\Payments;

use App\Models\Payment;

class PaymentManager
{
    protected ?PaymentGatewayInterface $gateway = null;

    public function __construct(?PaymentGatewayInterface $gateway = null)
    {
        $this->gateway = $gateway;
    }

    /**
     * Pata lango linalofanya kazi sasa hivi.
     */
    public function gateway(): PaymentGatewayInterface
    {
        if ($this->gateway) {
            return $this->gateway;
        }

        $mode = (string) config('services.pesapal.mode', 'simulation');
        $consumerKey = config('services.pesapal.consumer_key');
        $consumerSecret = config('services.pesapal.consumer_secret');

        // Ikiwa mode ni simulation au funguo za Pesapal hazijawekwa bado, tumia Simulation gateway
        if ($mode === 'simulation' || empty($consumerKey) || empty($consumerSecret)) {
            $this->gateway = new SimulationPaymentService();
        } else {
            $this->gateway = new PesapalService();
        }

        return $this->gateway;
    }

    /**
     * Anzisha malipo kwa kutumia lango lililochaguliwa.
     */
    public function initiateOrder(Payment $payment, array $options = []): array
    {
        return $this->gateway()->initiateOrder($payment, $options);
    }

    /**
     * Ulizia hadhi ya muamala.
     */
    public function getTransactionStatus(string $orderTrackingId): array
    {
        return $this->gateway()->getTransactionStatus($orderTrackingId);
    }
}
