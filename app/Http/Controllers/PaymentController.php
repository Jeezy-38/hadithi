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
     * Callback kutoka Pesapal au Simulation mtumiaji anaporudi kwenye tovuti.
     */
    public function callback(Request $request): RedirectResponse
    {
        $reference = $request->query('OrderMerchantReference')
            ?: $request->query('reference')
            ?: $request->query('merchant_reference');

        $trackingId = $request->query('OrderTrackingId')
            ?: $request->query('tracking_id');

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

        // Ulizia hadhi rasmi ya Pesapal kupitia API
        $effectiveTrackingId = $trackingId ?: $payment->gateway_reference;
        if ($effectiveTrackingId) {
            $statusResult = $this->paymentManager->getTransactionStatus($effectiveTrackingId);

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
     * Webhook IPN (Instant Payment Notification) kutoka Pesapal.
     */
    public function ipn(Request $request): JsonResponse
    {
        $notificationType = $request->query('OrderNotificationType');
        $trackingId = $request->query('OrderTrackingId');
        $reference = $request->query('OrderMerchantReference');

        if (! $trackingId || ! $reference) {
            return response()->json(['status' => '400', 'message' => 'Invalid parameters'], 400);
        }

        $payment = Payment::where('reference', $reference)
            ->orWhere('gateway_reference', $trackingId)
            ->first();

        if ($payment) {
            $statusResult = $this->paymentManager->getTransactionStatus($trackingId);

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
