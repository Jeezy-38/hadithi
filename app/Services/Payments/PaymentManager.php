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
     * Weka lango kwa mkono (kwa majaribio au mahitaji maalum).
     */
    public function setGateway(PaymentGatewayInterface $gateway): self
    {
        $this->gateway = $gateway;

        return $this;
    }

    /**
     * Pata jina la lango chaguo-msingi ('clickpesa', 'pesapal', au 'simulation').
     */
    public function defaultGatewayName(): string
    {
        $chosen = strtolower((string) config('services.payment_gateway', 'auto'));

        if ($chosen === 'clickpesa') {
            return 'clickpesa';
        }

        if ($chosen === 'pesapal') {
            return 'pesapal';
        }

        if ($chosen === 'simulation') {
            return 'simulation';
        }

        // Auto-detect
        $cpClientId = config('services.clickpesa.client_id');
        $cpApiKey = config('services.clickpesa.api_key');
        if (! empty($cpClientId) && ! empty($cpApiKey)) {
            return 'clickpesa';
        }

        $ppKey = config('services.pesapal.consumer_key');
        $ppSecret = config('services.pesapal.consumer_secret');
        $ppMode = config('services.pesapal.mode', 'simulation');
        if ($ppMode !== 'simulation' && ! empty($ppKey) && ! empty($ppSecret)) {
            return 'pesapal';
        }

        return 'simulation';
    }

    /**
     * Pata lango linalofanya kazi sasa hivi kulingana na usanidi wa .env.
     */
    public function gateway(?string $driver = null): PaymentGatewayInterface
    {
        if ($this->gateway && ! $driver) {
            return $this->gateway;
        }

        $chosen = strtolower((string) ($driver ?: config('services.payment_gateway', 'auto')));

        if ($chosen === 'clickpesa') {
            $clientId = config('services.clickpesa.client_id');
            $apiKey = config('services.clickpesa.api_key');

            if (! empty($clientId) && ! empty($apiKey)) {
                return new ClickPesaService();
            }

            return new SimulationPaymentService();
        }

        if ($chosen === 'pesapal') {
            $consumerKey = config('services.pesapal.consumer_key');
            $consumerSecret = config('services.pesapal.consumer_secret');
            $mode = config('services.pesapal.mode', 'simulation');

            if ($mode !== 'simulation' && ! empty($consumerKey) && ! empty($consumerSecret)) {
                return new PesapalService();
            }

            return new SimulationPaymentService();
        }

        if ($chosen === 'simulation') {
            return new SimulationPaymentService();
        }

        // Auto-detect: Ikiwa ClickPesa funguo zipo, itumie kwanza
        $cpClientId = config('services.clickpesa.client_id');
        $cpApiKey = config('services.clickpesa.api_key');
        if (! empty($cpClientId) && ! empty($cpApiKey)) {
            return new ClickPesaService();
        }

        // Vinginevyo ikiwa Pesapal ipo
        $ppKey = config('services.pesapal.consumer_key');
        $ppSecret = config('services.pesapal.consumer_secret');
        $ppMode = config('services.pesapal.mode', 'simulation');
        if ($ppMode !== 'simulation' && ! empty($ppKey) && ! empty($ppSecret)) {
            return new PesapalService();
        }

        return new SimulationPaymentService();
    }

    /**
     * Pata lango linalolingana na muamala uliopo (k.m. clickpesa au pesapal).
     */
    public function gatewayForPayment(Payment $payment): PaymentGatewayInterface
    {
        if ($this->gateway) {
            return $this->gateway;
        }

        if ($payment->gateway) {
            return $this->gateway($payment->gateway);
        }

        return $this->gateway();
    }

    /**
     * Anzisha malipo kwa kutumia lango lililochaguliwa.
     */
    public function initiateOrder(Payment $payment, array $options = []): array
    {
        $gateway = $this->gateway();

        // Rekodi lango lililotumika kwenye malipo
        if ($gateway instanceof ClickPesaService) {
            $payment->update(['gateway' => 'clickpesa']);
        } elseif ($gateway instanceof PesapalService) {
            $payment->update(['gateway' => 'pesapal']);
        } elseif ($gateway instanceof SimulationPaymentService) {
            $payment->update(['gateway' => 'simulation']);
        }

        return $gateway->initiateOrder($payment, $options);
    }

    /**
     * Ulizia hadhi ya muamala.
     */
    public function getTransactionStatus(string $orderTrackingId, ?string $driver = null): array
    {
        return $this->gateway($driver)->getTransactionStatus($orderTrackingId);
    }
}
