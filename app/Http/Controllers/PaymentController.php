<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Services\Payments\PaymentFulfillmentService;
use App\Services\Payments\PaymentManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function __construct(
        protected PaymentManager $paymentManager,
        protected PaymentFulfillmentService $fulfillment
    ) {}

    /**
     * Callback kutoka Pesapal, ClickPesa au Simulation mtumiaji anaporudi kwenye tovuti.
     */
    public function callback(Request $request): RedirectResponse
    {
        $reference = $request->query('OrderMerchantReference')
            ?: $request->query('reference')
            ?: $request->query('merchant_reference')
            ?: $request->query('orderReference');

        $trackingId = $request->query('OrderTrackingId')
            ?: $request->query('tracking_id')
            ?: $request->query('paymentReference');

        if (! $reference && ! $trackingId) {
            return redirect()->route('support')->with('error', 'Haikuweza kupata kumbukumbu ya muamala.');
        }

        $payment = Payment::where('reference', $reference)
            ->orWhere('gateway_reference', $trackingId)
            ->first();

        if (! $payment) {
            return redirect()->route('support')->with('error', 'Taarifa za muamala huu hazikupatikana.');
        }

        // Ikiwa ni simulation na paramita imethibitishwa moja kwa moja
        if ($request->query('simulation_action') === 'complete') {
            $this->fulfillment->fulfill($payment, [
                'payment_method' => $request->query('method', 'mobile_money'),
                'confirmation_code' => 'TEST-' . strtoupper(substr(md5($payment->reference), 0, 8)),
            ]);

            return redirect()->route('payments.receipt', ['reference' => $payment->reference])
                ->with('success', 'Mchango wako umethibitishwa kikamilifu! Jazakallahu Khayran.');
        }

        if ($request->query('simulation_action') === 'cancel') {
            $payment->update(['status' => 'failed']);

            return redirect()->route('payments.receipt', ['reference' => $payment->reference])
                ->with('error', 'Umeghairi muamala wa mchango.');
        }

        // Ulizia hadhi rasmi kupitia API ya lango lililotumika (ClickPesa au Pesapal)
        $effectiveTrackingId = $trackingId ?: $payment->gateway_reference ?: $payment->reference;
        if ($effectiveTrackingId) {
            $statusResult = $this->paymentManager->getTransactionStatus($effectiveTrackingId, $payment->gateway);

            if (($statusResult['status'] ?? '') === 'completed') {
                $this->fulfillment->fulfill($payment, $statusResult);

                return redirect()->route('payments.receipt', ['reference' => $payment->reference])
                    ->with('success', 'Mchango wako umethibitishwa kikamilifu! Jazakallahu Khayran.');
            }

            if (($statusResult['status'] ?? '') === 'failed') {
                $payment->update(['status' => 'failed']);

                return redirect()->route('payments.receipt', ['reference' => $payment->reference])
                    ->with('error', 'Muamala haukukamilika au umekataliwa.');
            }
        }

        return redirect()->route('payments.receipt', ['reference' => $payment->reference]);
    }

    /**
     * Webhook / IPN inayosaidia Pesapal na ClickPesa kwa pamoja.
     */
    public function ipn(Request $request): JsonResponse
    {
        // 1. Ukaguzi wa muundo wa Webhook ya ClickPesa (JSON)
        $event = $request->input('event');
        $cpData = $request->input('data', []);
        $cpReference = $cpData['orderReference'] ?? $request->input('orderReference');

        if ($cpReference) {
            $payment = Payment::where('reference', $cpReference)->first();

            if ($payment) {
                $status = strtoupper((string) ($cpData['status'] ?? $request->input('status', '')));

                if ($event === 'PAYMENT RECEIVED' || in_array($status, ['SUCCESS', 'SETTLED', 'COMPLETED'], true)) {
                    $this->fulfillment->fulfill($payment, [
                        'payment_method' => $cpData['channel'] ?? 'clickpesa',
                        'confirmation_code' => $cpData['paymentReference'] ?? null,
                    ]);
                } elseif ($event === 'PAYMENT FAILED' || in_array($status, ['FAILED', 'CANCELLED'], true)) {
                    $payment->update(['status' => 'failed']);
                }
            }

            return response()->json([
                'status' => 'success',
                'message' => 'ClickPesa webhook imepokelewa kikamilifu.',
            ]);
        }

        // 2. Ukaguzi wa muundo wa IPN ya Pesapal v3
        $notificationType = $request->query('OrderNotificationType') ?? $request->input('OrderNotificationType');
        $trackingId = $request->query('OrderTrackingId') ?? $request->input('OrderTrackingId');
        $reference = $request->query('OrderMerchantReference') ?? $request->input('OrderMerchantReference');

        if (! $trackingId || ! $reference) {
            return response()->json(['status' => '400', 'message' => 'Invalid parameters'], 400);
        }

        $payment = Payment::where('reference', $reference)
            ->orWhere('gateway_reference', $trackingId)
            ->first();

        if ($payment) {
            $statusResult = $this->paymentManager->getTransactionStatus($trackingId, 'pesapal');

            if (($statusResult['status'] ?? '') === 'completed') {
                $this->fulfillment->fulfill($payment, $statusResult);
            } elseif (($statusResult['status'] ?? '') === 'failed') {
                $payment->update(['status' => 'failed']);
            }
        }

        // Muundo rasmi wa jibu unaotarajiwa na Pesapal v3
        return response()->json([
            'orderNotificationType' => $notificationType,
            'orderTrackingId' => $trackingId,
            'orderMerchantReference' => $reference,
            'status' => '200',
        ]);
    }

    /**
     * Webhook mahususi kwa ajili ya ClickPesa (kwa urahisi wa usanidi kwenye ClickPesa Portal).
     */
    public function clickPesaWebhook(Request $request): JsonResponse
    {
        return $this->ipn($request);
    }

    /**
     * Ukurasa wa Risiti ya Kidigitali ya Sadaka Jariya / Supporter.
     */
    public function receipt(string $reference): View
    {
        $payment = Payment::where('reference', $reference)->firstOrFail();

        return view('payments.receipt', [
            'payment' => $payment,
        ]);
    }

    /**
     * Ukurasa wa Majaribio ya Ndani (Simulation Checkout).
     */
    public function simulate(string $reference): View
    {
        $payment = Payment::where('reference', $reference)->firstOrFail();

        return view('payments.simulation', [
            'payment' => $payment,
        ]);
    }
}
