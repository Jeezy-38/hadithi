<?php

namespace App\Livewire;

use App\Models\Payment;
use App\Services\Payments\PaymentManager;
use Illuminate\Support\Str;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Sadaka Jariya & Usaidizi · Bayt Al-Hadith')]
class SupportIndex extends Component
{
    public string $currency = 'TZS';

    public int $selectedPreset = 10000;

    public ?string $customAmount = null;

    public string $purpose = 'sadaka_jariya';

    public string $billingCycle = 'one_time';

    public string $paymentMethod = 'mobile_money';

    public string $donorName = '';

    public string $donorEmail = '';

    public string $donorPhone = '';

    public bool $isAnonymous = false;

    public string $message = '';

    public bool $checkoutInitiated = false;

    public ?string $activeReference = null;

    public ?float $activeAmount = null;

    public ?string $activeCurrency = null;

    public ?string $statusMessage = null;

    public function mount(): void
    {
        if (auth()->check()) {
            $user = auth()->user();
            $this->donorName = (string) $user->name;
            $this->donorEmail = (string) ($user->email ?? '');
        }
    }

    public function switchCurrency(string $currency): void
    {
        if (! in_array($currency, ['TZS', 'USD'], true)) {
            return;
        }

        $this->currency = $currency;
        $this->selectedPreset = $currency === 'TZS' ? 10000 : 5;
        $this->customAmount = null;
        $this->resetErrorBag();
    }

    public function selectPreset(int $amount): void
    {
        $this->selectedPreset = $amount;
        $this->customAmount = null;
        $this->resetErrorBag();
    }

    public function updatedCustomAmount(): void
    {
        if ($this->customAmount !== null && $this->customAmount !== '') {
            $this->selectedPreset = 0;
        }
    }

    /**
     * Namba ya simu hubadilishwa kuwa 255XXXXXXXXX mara tu mtumiaji anapomaliza kuandika.
     */
    public function updatedDonorPhone(): void
    {
        $this->donorPhone = $this->normalizePhone($this->donorPhone);
    }

    /**
     * 0712345678, 712345678, +255 712 345 678 -> 255712345678
     */
    protected function normalizePhone(?string $phone): string
    {
        $digits = preg_replace('/\D+/', '', (string) $phone);

        if ($digits === '') {
            return '';
        }

        if (str_starts_with($digits, '255')) {
            return $digits;
        }

        if (str_starts_with($digits, '0')) {
            return '255' . substr($digits, 1);
        }

        if (strlen($digits) === 9) {
            return '255' . $digits;
        }

        return $digits;
    }

    public function initiatePayment(): void
    {
        $this->resetErrorBag();

        $amount = $this->resolveAmount();

        $minAmount = $this->currency === 'TZS' ? 1000 : 1;

        if ($amount < $minAmount) {
            $minFormatted = $this->currency === 'TZS' ? 'TZS 1,000' : '$1';
            $this->addError('amount', "Kiasi cha chini cha mchango ni {$minFormatted}.");

            return;
        }

        $this->donorPhone = $this->normalizePhone($this->donorPhone);

        $this->validate([
            'donorEmail' => 'nullable|email|max:190',
            'donorPhone' => ['nullable', 'regex:/^255[0-9]{9}$/'],
            'message' => 'nullable|string|max:280',
            'paymentMethod' => 'required|in:mobile_money,card',
            'purpose' => 'required|in:sadaka_jariya,supporter_membership',
            'billingCycle' => 'required|in:one_time,monthly',
        ], [
            'donorEmail.email' => 'Tafadhali ingiza anwani halali ya barua pepe.',
            'donorPhone.regex' => 'Namba ya simu iwe katika muundo wa 255XXXXXXXXX (mfano 255712345678).',
            'message.max' => 'Dua au ujumbe hauwezi kuzidi herufi 280.',
        ]);

        $prefix = $this->purpose === 'supporter_membership' ? 'SUPP' : 'SADAKA';
        $reference = sprintf('%s-%s-%s', $prefix, now()->format('Ymd'), strtoupper(Str::random(6)));

        $tier = $this->resolveTier($amount, $this->currency);

        $payment = Payment::create([
            'user_id' => auth()->id(),
            'reference' => $reference,
            'donor_name' => $this->isAnonymous ? null : ($this->donorName ?: null),
            'donor_email' => $this->donorEmail ?: null,
            'donor_phone' => $this->donorPhone ?: null,
            'amount' => $amount,
            'currency' => $this->currency,
            'purpose' => $this->purpose,
            'tier' => $tier,
            'billing_cycle' => $this->billingCycle,
            'payment_method' => $this->paymentMethod,
            'gateway' => app(PaymentManager::class)->defaultGatewayName(),
            'status' => 'pending',
            'is_anonymous' => $this->isAnonymous,
            'message' => $this->isAnonymous ? null : ($this->message ?: null),
            'metadata' => [
                'user_agent' => request()->userAgent(),
                'ip' => request()->ip(),
            ],
        ]);

        $this->checkoutInitiated = true;
        $this->activeReference = $payment->reference;
        $this->activeAmount = $amount;
        $this->activeCurrency = $this->currency;
    }

    public function processCheckout(PaymentManager $manager)
    {
        $this->resetErrorBag();

        if (! $this->activeReference) {
            $this->addError('checkout', 'Hakuna muamala ulioandaliwa bado.');

            return;
        }

        $payment = Payment::where('reference', $this->activeReference)->first();
        if (! $payment) {
            $this->addError('checkout', 'Taarifa za muamala huu hazikupatikana.');

            return;
        }

        $result = $manager->initiateOrder($payment);

        if (($result['status'] ?? '') === 'success' && ! empty($result['redirect_url'])) {
            return redirect()->away($result['redirect_url']);
        }

        $this->addError('checkout', $result['message'] ?? 'Hitilafu ya kuanzisha lango la malipo. Tafadhali jaribu tena.');
    }

    public function cancelCheckout(): void
    {
        $this->checkoutInitiated = false;
        $this->activeReference = null;
        $this->activeAmount = null;
        $this->activeCurrency = null;
    }

    protected function resolveAmount(): float
    {
        if ($this->customAmount !== null && trim($this->customAmount) !== '') {
            return (float) preg_replace('/[^0-9.]/', '', $this->customAmount);
        }

        return (float) $this->selectedPreset;
    }

    protected function resolveTier(float $amount, string $currency): string
    {
        if ($currency === 'TZS') {
            if ($amount >= 50000) {
                return 'gold';
            }
            if ($amount >= 20000) {
                return 'silver';
            }

            return 'supporter';
        }

        // USD
        if ($amount >= 50) {
            return 'gold';
        }
        if ($amount >= 20) {
            return 'silver';
        }

        return 'supporter';
    }

    public function render()
    {
        $recentDonations = Payment::publicDonations()
            ->recent()
            ->limit(6)
            ->get();

        $stats = [
            'total_supporters' => Payment::completed()->distinct('user_id')->count('user_id') ?: 12,
            'active_country_reach' => 'Tanzania & Kimataifa',
        ];

        return view('livewire.support-index', [
            'recentDonations' => $recentDonations,
            'stats' => $stats,
        ])->layout('components.layouts.app', ['title' => 'Sadaka Jariya & Usaidizi · Bayt Al-Hadith']);
    }
}
