<div class="support-page-wrapper">
    <link rel="stylesheet" href="{{ asset('css/support.css') }}?v={{ filemtime(public_path('css/support.css')) }}">

    <div class="support-container support-container--form">
        {{-- Eneo la Fomu ya Malipo Pekee --}}
        <div class="support-card support-card--form">
            <div class="support-card-header support-card-header--form">
                <span class="support-mini-badge support-mini-badge--form" data-i18n="pg_su_badge">
                    SADAKA JARIYA
                </span>
                <h1 class="support-card-title support-card-title--form" data-i18n="pg_su_title">
                    Fomu ya Mchango &amp; Malipo
                </h1>
                <p class="support-card-subtitle support-card-subtitle--form" data-i18n="pg_su_subtitle">
                    Kamilisha mchango wako wa Sadaka Jariya kwa urahisi na usalama kupitia ClickPesa.
                </p>
            </div>

            {{-- Ikiwa Malipo Yameanzishwa --}}
            @if ($checkoutInitiated)
                <div class="support-confirmation-card">
                    <div class="conf-badge" data-i18n="pg_su_conf_badge">MUAMALA UMEANDALIWA</div>
                    <div class="conf-amount-row">
                        <span class="conf-amount-label" data-i18n="pg_su_conf_amount">Kiasi Kinacholipwa</span>
                        <span class="conf-amount-val">
                            {{ $activeCurrency === 'TZS' ? 'TZS ' . number_format($activeAmount) : '$' . number_format($activeAmount, 2) }}
                        </span>
                    </div>
                    <div class="conf-ref-row">
                        <span data-i18n="pg_su_conf_ref">Kumbukumbu Namba:</span> <strong>{{ $activeReference }}</strong>
                    </div>
                    <p class="conf-instructions" data-i18n="pg_su_conf_text">
                        Muamala wako umesajiliwa kikamilifu. Bonyeza kitufe hapa chini ili uelekezwe kwenye lango la ClickPesa kuthibitisha malipo kwenye simu yako au kadi.
                    </p>
                    <div class="support-actions-row">
                        <button type="button" wire:click="cancelCheckout" class="preset-chip-btn" style="flex: 1;" data-i18n="pg_su_conf_edit">
                            Rekebisha Fomu
                        </button>
                        <button type="button" wire:click="processCheckout" wire:loading.attr="disabled" class="support-submit-btn" style="flex: 1.5;">
                            <span wire:loading.remove wire:target="processCheckout" data-i18n="pg_su_conf_pay">Thibitisha na Lipa Sasa</span>
                            <span wire:loading wire:target="processCheckout" data-i18n="pg_su_conf_connecting">Inaunganisha na Lango...</span>
                        </button>
                    </div>
                    @error('checkout')
                        <div class="support-error-box">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            @else
                <div class="support-form-grid">
                <div class="support-col support-col-amount">
                {{-- 1. Chagua Kusudi (Sadaka Jariya au Msaidizi wa App) --}}
                <div class="support-purpose-tabs">
                    <button type="button" wire:click="$set('purpose', 'sadaka_jariya')" class="purpose-tab-btn {{ $purpose === 'sadaka_jariya' ? 'active' : '' }}">
                        <strong data-i18n="pg_su_tab1">Sadaka Jariya</strong>
                        <span data-i18n="pg_su_tab1_sub">Mchango wa hiari wa da'wah</span>
                    </button>
                    <button type="button" wire:click="$set('purpose', 'supporter_membership')" class="purpose-tab-btn {{ $purpose === 'supporter_membership' ? 'active' : '' }}">
                        <strong data-i18n="pg_su_tab2">Kuwa Supporter</strong>
                        <span data-i18n="pg_su_tab2_sub">Beji maalum &amp; huduma za VIP</span>
                    </button>
                </div>

                {{-- 2. Chagua Sarafu (TZS vs USD) --}}
                <div class="support-currency-row">
                    <span class="support-label" data-i18n="pg_su_currency">Chagua Sarafu:</span>
                    <div class="support-currency-toggle">
                        <button type="button" wire:click="switchCurrency('TZS')" class="curr-toggle-btn {{ $currency === 'TZS' ? 'active' : '' }}" data-i18n="pg_su_tzs">
                            TZS (Shilingi)
                        </button>
                        <button type="button" wire:click="switchCurrency('USD')" class="curr-toggle-btn {{ $currency === 'USD' ? 'active' : '' }}" data-i18n="pg_su_usd">
                            USD (Dola)
                        </button>
                    </div>
                </div>

                {{-- 3. Viwango Vilivyopendekezwa (Preset Chips) --}}
                <div class="support-input-group">
                    <label class="support-label" data-i18n="pg_su_amount">Chagua Kiasi cha Mchango:</label>
                    <div class="support-presets-grid">
                        @if ($currency === 'TZS')
                            <button type="button" wire:click="selectPreset(3000)" class="preset-chip-btn {{ $selectedPreset === 3000 && empty($customAmount) ? 'active' : '' }}">
                                <span class="chip-cur">TZS </span>3,000
                            </button>
                            <button type="button" wire:click="selectPreset(10000)" class="preset-chip-btn {{ $selectedPreset === 10000 && empty($customAmount) ? 'active' : '' }}">
                                <span class="chip-cur">TZS </span>10,000
                            </button>
                            <button type="button" wire:click="selectPreset(25000)" class="preset-chip-btn {{ $selectedPreset === 25000 && empty($customAmount) ? 'active' : '' }}">
                                <span class="chip-cur">TZS </span>25,000
                            </button>
                            <button type="button" wire:click="selectPreset(50000)" class="preset-chip-btn {{ $selectedPreset === 50000 && empty($customAmount) ? 'active' : '' }}">
                                <span class="chip-cur">TZS </span>50,000
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
                    <label for="customAmount" class="support-label" data-i18n="pg_su_custom">Au ingiza kiasi unachopenda mwenyewe:</label>
                    <div class="support-input-wrap">
                        <span class="support-input-prefix">{{ $currency }}</span>
                        <input type="number" id="customAmount" wire:model.live.debounce.350ms="customAmount" class="support-input" placeholder="{{ $currency === 'TZS' ? 'Mfano: 20000' : 'Mfano: 25' }}" data-i18n-placeholder="{{ $currency === 'TZS' ? 'pg_su_custom_ph_tzs' : 'pg_su_custom_ph_usd' }}" min="{{ $currency === 'TZS' ? 1000 : 1 }}" step="{{ $currency === 'TZS' ? 500 : 1 }}">
                    </div>
                    @error('amount')
                        <span class="support-error support-error--block">{{ $message }}</span>
                    @enderror
                </div>

                </div>
                <div class="support-col support-col-details">
                {{-- 5. Njia ya Malipo (Payment Method) --}}
                <div class="support-input-group">
                    <label class="support-label" data-i18n="pg_su_method">Njia Unayopendelea Kulipa:</label>
                    <div class="payment-methods-grid">
                        <label class="payment-method-card {{ $paymentMethod === 'mobile_money' ? 'active' : '' }}">
                            <input type="radio" wire:model="paymentMethod" value="mobile_money" class="pm-radio">
                            <div class="pm-meta">
                                <strong data-i18n="pg_su_mobile">Mitandao ya Simu</strong>
                                <span data-i18n="pg_su_mobile_sub">M-Pesa, Tigo, Airtel, Halo</span>
                            </div>
                        </label>
                        <label class="payment-method-card {{ $paymentMethod === 'card' ? 'active' : '' }}">
                            <input type="radio" wire:model="paymentMethod" value="card" class="pm-radio">
                            <div class="pm-meta">
                                <strong data-i18n="pg_su_card">Kadi ya Benki</strong>
                                <span data-i18n="pg_su_card_sub">Visa, Mastercard (Ndani/Nje)</span>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- 6. Taarifa za Mchangiaji --}}
                <div class="support-pair">
                    <div class="support-input-group">
                        <label for="donorName" class="support-label" data-i18n="pg_su_name">Jina Lako:</label>
                        <input type="text" id="donorName" wire:model="donorName" class="support-input support-input-plain" placeholder="Jina au herufi za mwanzo" data-i18n-placeholder="pg_su_name_ph" autocomplete="name">
                    </div>
                    <div class="support-input-group">
                        <label for="donorPhone" class="support-label" data-i18n="pg_su_phone">Simu (Mobile Money):</label>
                        <input type="tel" id="donorPhone" inputmode="numeric" wire:model.blur="donorPhone" class="support-input support-input-plain" placeholder="255712345678" autocomplete="tel">
                        @error('donorPhone')
                            <span class="support-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="support-input-group">
                    <label for="donorEmail" class="support-label" data-i18n="pg_su_email">Barua Pepe (Email):</label>
                    <input type="email" id="donorEmail" wire:model="donorEmail" class="support-input support-input-plain" placeholder="Kwa ajili ya risiti" data-i18n-placeholder="pg_su_email_ph" autocomplete="email">
                    @error('donorEmail')
                        <span class="support-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="support-checkbox-row support-checkbox-row--compact">
                    <input type="checkbox" id="isAnonymous" wire:model="isAnonymous">
                    <label for="isAnonymous" class="support-checkbox-label">
                        <strong data-i18n="pg_su_anon">Changia bila kutaja jina (Anonymous)</strong>
                        <span class="support-checkbox-hint" data-i18n="pg_su_anon_sub">Jina lako halitaonekana hadharani.</span>
                    </label>
                </div>

                {{-- 7. Dua au Ujumbe wa Hiari (hufichwa hadi ubonyeze) --}}
                <div class="support-input-group" x-data="{ open: {{ $message ? 'true' : 'false' }} }">
                    <button type="button" class="support-link-btn" x-show="!open" x-on:click="open = true" data-i18n="pg_su_add_msg">+ Ongeza dua au ujumbe (hiari)</button>
                    <div x-show="open" x-cloak>
                        <label for="message" class="support-label" data-i18n="pg_su_msg">Dua au Ujumbe wa Hiari:</label>
                        <textarea id="message" wire:model="message" class="support-textarea" rows="2" placeholder="Mfano: Allah awabariki katika kazi hii njema..." data-i18n-placeholder="pg_su_msg_ph"></textarea>
                        @error('message')
                            <span class="support-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- Kitufe cha Kuanzisha Malipo (hubaki chini kwenye simu) --}}
                @php
                    $shownAmount = (float) ($customAmount ?: $selectedPreset ?: 0);
                    $shownLabel = $shownAmount > 0
                        ? ($currency === 'TZS' ? 'TZS ' . number_format($shownAmount) : '$' . number_format($shownAmount, 2))
                        : null;
                @endphp
                <div class="support-submit-bar">
                    <button type="button" wire:click="initiatePayment" wire:loading.attr="disabled" class="support-submit-btn">
                        <span wire:loading.remove wire:target="initiatePayment"><span data-i18n="{{ $shownLabel ? 'pg_su_donate' : 'pg_su_continue' }}">{{ $shownLabel ? 'Changia' : 'Endelea na Malipo' }}</span>{{ $shownLabel ? ' ' . $shownLabel : '' }} &rarr;</span>
                        <span wire:loading wire:target="initiatePayment" data-i18n="pg_su_preparing">Inaandaa muamala...</span>
                    </button>
                </div>
                </div>
                </div>
            @endif
        </div>
    </div>
</div>
