<div class="support-page-wrapper">
    <link rel="stylesheet" href="{{ asset('css/support.css') }}">

    <div class="support-container" style="max-width: 660px; padding: 2rem 1rem 4rem;">
        {{-- Eneo la Fomu ya Malipo Pekee --}}
        <div class="support-card" style="margin: 0 auto; border: 1px solid rgba(226, 232, 240, 0.16); background: #021e28; border-radius: 18px; box-shadow: 0 16px 40px rgba(0, 0, 0, 0.5);">
            <div class="support-card-header" style="text-align: center; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 1px solid rgba(226, 232, 240, 0.1);">
                <span class="support-mini-badge" style="display: inline-block; background: rgba(226, 232, 240, 0.08); border: 1px solid rgba(226, 232, 240, 0.2); color: #e2e8f0; font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; padding: 0.28rem 0.85rem; border-radius: 999px; margin-bottom: 0.6rem;">
                    SADAKA JARIYA
                </span>
                <h1 class="support-card-title" style="justify-content: center; margin-bottom: 0.4rem; font-size: 1.5rem; font-weight: 800; color: #ffffff;">
                    Fomu ya Mchango &amp; Malipo
                </h1>
                <p style="color: #94a3b8; font-size: 0.88rem; margin: 0; line-height: 1.5;">
                    Kamilisha mchango wako wa Sadaka Jariya kwa urahisi na usalama kupitia ClickPesa.
                </p>
            </div>

            {{-- Ikiwa Malipo Yameanzishwa --}}
            @if ($checkoutInitiated)
                <div class="support-confirmation-card">
                    <div class="conf-badge">MUAMALA UMEANDALIWA</div>
                    <div class="conf-amount-row">
                        <span class="conf-amount-label">Kiasi Kinacholipwa</span>
                        <span class="conf-amount-val">
                            {{ $activeCurrency === 'TZS' ? 'TZS ' . number_format($activeAmount) : '$' . number_format($activeAmount, 2) }}
                        </span>
                    </div>
                    <div class="conf-ref-row">
                        Kumbukumbu Namba: <strong>{{ $activeReference }}</strong>
                    </div>
                    <p class="conf-instructions">
                        Muamala wako umesajiliwa kikamilifu. Bonyeza kitufe hapa chini ili uelekezwe kwenye lango la ClickPesa kuthibitisha malipo kwenye simu yako au kadi.
                    </p>
                    <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
                        <button type="button" wire:click="cancelCheckout" class="preset-chip-btn" style="flex: 1;">
                            Rekebisha Fomu
                        </button>
                        <button type="button" wire:click="processCheckout" wire:loading.attr="disabled" class="support-submit-btn" style="flex: 1.5;">
                            <span wire:loading.remove wire:target="processCheckout">Thibitisha na Lipa Sasa</span>
                            <span wire:loading wire:target="processCheckout">Inaunganisha na Lango...</span>
                        </button>
                    </div>
                    @error('checkout')
                        <div style="color: #ef4444; font-size: 0.85rem; margin-top: 0.75rem; text-align: center; background: rgba(239, 68, 68, 0.1); padding: 0.6rem; border-radius: 8px;">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            @else
                {{-- 1. Chagua Kusudi (Sadaka Jariya au Msaidizi wa App) --}}
                <div class="support-purpose-tabs">
                    <button type="button" wire:click="$set('purpose', 'sadaka_jariya')" class="purpose-tab-btn {{ $purpose === 'sadaka_jariya' ? 'active' : '' }}">
                        <strong>Sadaka Jariya</strong>
                        <span>Mchango wa hiari wa da'wah</span>
                    </button>
                    <button type="button" wire:click="$set('purpose', 'supporter_membership')" class="purpose-tab-btn {{ $purpose === 'supporter_membership' ? 'active' : '' }}">
                        <strong>Kuwa Supporter</strong>
                        <span>Beji maalum &amp; huduma za VIP</span>
                    </button>
                </div>

                {{-- 2. Chagua Sarafu (TZS vs USD) --}}
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                    <span class="support-label" style="margin: 0; font-weight: 600; color: #e2e8f0;">Chagua Sarafu:</span>
                    <div class="support-currency-toggle">
                        <button type="button" wire:click="switchCurrency('TZS')" class="curr-toggle-btn {{ $currency === 'TZS' ? 'active' : '' }}">
                            TZS (Shilingi)
                        </button>
                        <button type="button" wire:click="switchCurrency('USD')" class="curr-toggle-btn {{ $currency === 'USD' ? 'active' : '' }}">
                            USD (Dola)
                        </button>
                    </div>
                </div>

                {{-- 3. Viwango Vilivyopendekezwa (Preset Chips) --}}
                <div class="support-input-group">
                    <label class="support-label" style="font-weight: 600; color: #e2e8f0;">Chagua Kiasi cha Mchango:</label>
                    <div class="support-presets-grid">
                        @if ($currency === 'TZS')
                            <button type="button" wire:click="selectPreset(3000)" class="preset-chip-btn {{ $selectedPreset === 3000 && empty($customAmount) ? 'active' : '' }}">
                                TZS 3,000
                            </button>
                            <button type="button" wire:click="selectPreset(10000)" class="preset-chip-btn {{ $selectedPreset === 10000 && empty($customAmount) ? 'active' : '' }}">
                                TZS 10,000
                            </button>
                            <button type="button" wire:click="selectPreset(25000)" class="preset-chip-btn {{ $selectedPreset === 25000 && empty($customAmount) ? 'active' : '' }}">
                                TZS 25,000
                            </button>
                            <button type="button" wire:click="selectPreset(50000)" class="preset-chip-btn {{ $selectedPreset === 50000 && empty($customAmount) ? 'active' : '' }}">
                                TZS 50,000
                            </button>
                        @else
                            <button type="button" wire:click="selectPreset(2)" class="preset-chip-btn {{ $selectedPreset === 2 && empty($customAmount) ? 'active' : '' }}">
                                $2
                            </button>
                            <button type="button" wire:click="selectPreset(5)" class="preset-chip-btn {{ $selectedPreset === 5 && empty($customAmount) ? 'active' : '' }}">
                                $5
                            </button>
                            <button type="button" wire:click="selectPreset(15)" class="preset-chip-btn {{ $selectedPreset === 15 && empty($customAmount) ? 'active' : '' }}">
                                $15
                            </button>
                            <button type="button" wire:click="selectPreset(50)" class="preset-chip-btn {{ $selectedPreset === 50 && empty($customAmount) ? 'active' : '' }}">
                                $50
                            </button>
                        @endif
                    </div>
                </div>

                {{-- 4. Kiasi Maalum (Custom Amount) --}}
                <div class="support-input-group">
                    <label for="customAmount" class="support-label" style="font-weight: 600; color: #e2e8f0;">Au ingiza kiasi unachopenda mwenyewe:</label>
                    <div class="support-input-wrap">
                        <span class="support-input-prefix">{{ $currency }}</span>
                        <input type="number" id="customAmount" wire:model.live="customAmount" class="support-input" placeholder="{{ $currency === 'TZS' ? 'Mfano: 20000' : 'Mfano: 25' }}" min="{{ $currency === 'TZS' ? 1000 : 1 }}" step="{{ $currency === 'TZS' ? 500 : 1 }}">
                    </div>
                    @error('amount')
                        <span style="color: #ef4444; font-size: 0.8rem; display: block; margin-top: 0.35rem;">{{ $message }}</span>
                    @enderror
                </div>

                {{-- 5. Njia ya Malipo (Payment Method) --}}
                <div class="support-input-group">
                    <label class="support-label" style="font-weight: 600; color: #e2e8f0;">Njia Unayopendelea Kulipa:</label>
                    <div class="payment-methods-grid">
                        <label class="payment-method-card {{ $paymentMethod === 'mobile_money' ? 'active' : '' }}">
                            <input type="radio" wire:model.live="paymentMethod" value="mobile_money" class="pm-radio">
                            <div class="pm-meta">
                                <strong>Mitandao ya Simu</strong>
                                <span>M-Pesa, Tigo, Airtel, Halo</span>
                            </div>
                        </label>
                        <label class="payment-method-card {{ $paymentMethod === 'card' ? 'active' : '' }}">
                            <input type="radio" wire:model.live="paymentMethod" value="card" class="pm-radio">
                            <div class="pm-meta">
                                <strong>Kadi ya Benki</strong>
                                <span>Visa, Mastercard (Ndani/Nje)</span>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- 6. Taarifa za Mchangiaji --}}
                <div class="support-input-group">
                    <label for="donorName" class="support-label" style="font-weight: 600; color: #e2e8f0;">Jina Lako:</label>
                    <input type="text" id="donorName" wire:model.defer="donorName" class="support-input support-input-plain" placeholder="Jina au herufi za mwanzo">
                </div>

                <div class="support-checkbox-row" style="margin-bottom: 1rem;">
                    <input type="checkbox" id="isAnonymous" wire:model.live="isAnonymous">
                    <label for="isAnonymous" class="support-checkbox-label">
                        <strong>Changia bila kutaja jina (Anonymous)</strong><br>
                        Jina lako halitaonekana hadharani.
                    </label>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem;">
                    <div class="support-input-group">
                        <label for="donorEmail" class="support-label" style="font-weight: 600; color: #e2e8f0;">Barua Pepe (Email):</label>
                        <input type="email" id="donorEmail" wire:model.defer="donorEmail" class="support-input support-input-plain" placeholder="Kwa ajili ya risiti">
                        @error('donorEmail')
                            <span style="color: #ef4444; font-size: 0.8rem;">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="support-input-group">
                        <label for="donorPhone" class="support-label" style="font-weight: 600; color: #e2e8f0;">Namba ya Simu:</label>
                        <input type="tel" id="donorPhone" wire:model.defer="donorPhone" class="support-input support-input-plain" placeholder="07XXXXXXXX au +255...">
                    </div>
                </div>

                {{-- 7. Dua au Ujumbe wa Hiari --}}
                <div class="support-input-group">
                    <label for="message" class="support-label" style="font-weight: 600; color: #e2e8f0;">Dua au Ujumbe wa Hiari:</label>
                    <textarea id="message" wire:model.defer="message" class="support-textarea" placeholder="Mfano: Allah awabariki katika kazi hii njema..."></textarea>
                    @error('message')
                        <span style="color: #ef4444; font-size: 0.8rem;">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Kitufe cha Kuanzisha Malipo --}}
                <button type="button" wire:click="initiatePayment" class="support-submit-btn" style="margin-top: 0.5rem;">
                    <span>Endelea na Malipo</span>
                    <span aria-hidden="true">→</span>
                </button>
            @endif
        </div>
    </div>
</div>
