<div class="support-page-wrapper">
    <link rel="stylesheet" href="{{ asset('css/support.css') }}">

    <div class="support-container">
        {{-- Hero ya Sadaka Jariya na Usaidizi --}}
        <section class="support-hero">
            <div class="support-hero-badge">
                <span>SADAKA JARIYA &amp; USHIRIKIANO WA ELIMU</span>
            </div>
            <h1 class="support-hero-title">
                Kuza Elimu ya Mtume ﷺ<br>
                <em>Kama Sadaka Yenye Kuendelea.</em>
            </h1>
            <p class="support-hero-desc">
                Bayt Al-Hadith inalenga kufanya Hadithi 1,000 sahihi, Dua 2,000 na Qur'ani nzima ziweze kusomwa na kusikilizwa na kila mtu bure bila vizuizi vya matangazo. Mchango wako unafadhili uendeshaji wa seva, sauti za wasomaji, na uboreshaji wa kudumu.
            </p>

            {{-- Hadithi ya Sadaka Jariya --}}
            <div class="support-hadith-box">
                <div class="support-hadith-arabic">
                    «إِذَا مَاتَ الإِنْسَانُ انْقَطَعَ عَنْهُ عَمَلُهُ إِلاَّ مِنْ ثَلاَثَةٍ: إِلاَّ مِنْ صَدَقَةٍ جَارِيَةٍ، أَوْ عِلْمٍ يُنْتَفَعُ بِهِ، أَوْ وَلَدٍ صَالِحٍ يَدْعُو لَهُ»
                </div>
                <div class="support-hadith-swahili">
                    "Mwanadamu anapofariki, amali zake zote hukatika isipokuwa mambo matatu: Sadaka yenye kuendelea (Sadaka Jariya), elimu yenye manufaa, au mtoto mwema anayemuombea dua."
                </div>
                <div class="support-hadith-ref">
                    Sahih Muslim · Hadithi Nambari 1631
                </div>
            </div>
        </section>

        {{-- Nguzo 3 za Matumizi ya Mchango --}}
        <section class="support-pillars" aria-label="Madhumuni ya Mchango">
            <div class="support-pillar-card">
                <div class="support-pillar-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="2" y="2" width="20" height="8" rx="2" ry="2"></rect>
                        <rect x="2" y="14" width="20" height="8" rx="2" ry="2"></rect>
                        <line x1="6" y1="6" x2="6.01" y2="6"></line>
                        <line x1="6" y1="18" x2="6.01" y2="18"></line>
                    </svg>
                </div>
                <h3 class="support-pillar-title">Seva &amp; Upatikanaji wa Haraka</h3>
                <p class="support-pillar-desc">Kuhakikisha app inafanya kazi masaa 24/7 kwa kasi ya juu popote duniani bila kukatika.</p>
            </div>

            <div class="support-pillar-card">
                <div class="support-pillar-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3z"></path>
                        <path d="M19 10v2a7 7 0 0 1-14 0v-2"></path>
                        <line x1="12" y1="19" x2="12" y2="23"></line>
                        <line x1="8" y1="23" x2="16" y2="23"></line>
                    </svg>
                </div>
                <h3 class="support-pillar-title">Sauti za Wasomaji Bora</h3>
                <p class="support-pillar-desc">Kufadhili utayarishaji na uhifadhi wa sauti za usomaji wa Kiarabu na tafsiri ya Kiswahili.</p>
            </div>

            <div class="support-pillar-card">
                <div class="support-pillar-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                    </svg>
                </div>
                <h3 class="support-pillar-title">Maudhui Huria Bila Matangazo</h3>
                <p class="support-pillar-desc">Kuzuia matangazo ya kibiashara yanayochafua heshima ya aya na hadithi za Mtume ﷺ.</p>
            </div>
        </section>

        {{-- Eneo Kuu la Malipo na Fomu --}}
        <div class="support-workspace">
            {{-- Upande wa Fomu --}}
            <div class="support-card">
                <h2 class="support-card-title">
                    <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--gold);">
                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                    </svg>
                    <span>Fomu ya Mchango &amp; Usaidizi</span>
                </h2>

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
                            Muamala wako umesajiliwa kikamilifu. Hatua inayofuata ni kuunganishwa na lango la malipo (Payment Gateway) ambapo utathibitisha muamala kwenye simu yako au kadi.
                        </p>
                        <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
                            <button type="button" wire:click="cancelCheckout" class="preset-chip-btn" style="flex: 1;">
                                Rekebisha Fomu
                            </button>
                            <button type="button" class="support-submit-btn" style="flex: 1.5;" onclick="alert('Lango la malipo litaunganishwa katika Hatua ya 3 (Pesapal / Flutterwave). Kumbukumbu yako ya muamala ni: {{ $activeReference }}')">
                                Thibitisha na Lipa Sasa
                            </button>
                        </div>
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
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                        <span class="support-label" style="margin: 0;">Chagua Sarafu:</span>
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
                        <label class="support-label">Chagua Kiasi cha Mchango:</label>
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
                        <label for="customAmount" class="support-label">Au ingiza kiasi unachopenda mwenyewe:</label>
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
                        <label class="support-label">Njia Unayopendelea Kulipa:</label>
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
                        <label for="donorName" class="support-label">Jina Lako:</label>
                        <input type="text" id="donorName" wire:model.defer="donorName" class="support-input support-input-plain" placeholder="Jina au herufi za mwanzo">
                    </div>

                    <div class="support-checkbox-row">
                        <input type="checkbox" id="isAnonymous" wire:model.live="isAnonymous">
                        <label for="isAnonymous" class="support-checkbox-label">
                            <strong>Changia bila kutaja jina (Anonymous)</strong><br>
                            Jina lako halitaonekana hadharani kwenye orodha ya wachangiaji wa Sadaka Jariya.
                        </label>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem;">
                        <div class="support-input-group">
                            <label for="donorEmail" class="support-label">Barua Pepe (Email):</label>
                            <input type="email" id="donorEmail" wire:model.defer="donorEmail" class="support-input support-input-plain" placeholder="Kwa ajili ya risiti">
                            @error('donorEmail')
                                <span style="color: #ef4444; font-size: 0.8rem;">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="support-input-group">
                            <label for="donorPhone" class="support-label">Namba ya Simu:</label>
                            <input type="tel" id="donorPhone" wire:model.defer="donorPhone" class="support-input support-input-plain" placeholder="07XXXXXXXX au +255...">
                        </div>
                    </div>

                    {{-- 7. Dua au Ujumbe wa Hiari --}}
                    <div class="support-input-group">
                        <label for="message" class="support-label">Dua au Ujumbe wa Moyoni (Hiari):</label>
                        <textarea id="message" wire:model.defer="message" class="support-textarea" placeholder="Mfano: Namuombea mama yangu maghfirah, au Allah atufungulie kheri..."></textarea>
                        @error('message')
                            <span style="color: #ef4444; font-size: 0.8rem;">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Kitufe cha Kuanzisha Malipo --}}
                    <button type="button" wire:click="initiatePayment" class="support-submit-btn">
                        <span>Endelea na Mchango</span>
                        <span aria-hidden="true">→</span>
                    </button>
                @endif
            </div>

            {{-- Upande wa Manufaa ya Kuwa Supporter --}}
            <aside class="support-benefits-card">
                <h3 style="font-size: 1.15rem; font-weight: 700; color: var(--text-primary); margin-bottom: 1.25rem;">
                    Faida za Kuwa Msaidizi (Supporter)
                </h3>

                <div class="benefit-item">
                    <div class="benefit-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="8" r="7"></circle>
                            <polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline>
                        </svg>
                    </div>
                    <div class="benefit-content">
                        <strong>Beji ya Heshima (Supporter Badge)</strong>
                        <p>Wasifu wako unakuwa na alama maalum ya dhahabu inayoonyesha mchango wako kwa jamii.</p>
                    </div>
                </div>

                <div class="benefit-item">
                    <div class="benefit-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <polyline points="7 10 12 15 17 10"></polyline>
                            <line x1="12" y1="15" x2="12" y2="3"></line>
                        </svg>
                    </div>
                    <div class="benefit-content">
                        <strong>Kupakua Sauti Zote (Offline Audio)</strong>
                        <p>Uwezo wa kuhifadhi sauti zote za hadithi na dua kwenye simu yako ili kusikiliza bila mtandao.</p>
                    </div>
                </div>

                <div class="benefit-item">
                    <div class="benefit-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                    </div>
                    <div class="benefit-content">
                        <strong>Ujira wa Kudumu (Thawab)</strong>
                        <p>Kila mtu anayesoma hadithi, akajifunza dua, au akafanya dhikr kupitia app hii, unapata sehemu ya ujira wake insha'Allah.</p>
                    </div>
                </div>

                <div style="border-top: 1px solid var(--border-subtle); padding-top: 1.25rem; margin-top: 1rem;">
                    <div style="font-size: 0.8rem; color: var(--text-dim); text-align: center;">
                        Bayt Al-Hadith inafuata maadili ya Kiislamu ya uaminifu na uwazi katika matumizi ya michango yote.
                    </div>
                </div>
            </aside>
        </div>

        {{-- Ukuta wa Sadaka Jariya (Recent Infaq & Dua Wall) --}}
        @if ($recentDonations->isNotEmpty())
            <section class="support-wall" aria-label="Wachangiaji wa Hivi Karibuni">
                <div class="support-wall-header">
                    <div>
                        <h3 class="support-wall-title">Dua &amp; Sadaka za Hivi Karibuni</h3>
                        <span style="font-size: 0.85rem; color: var(--text-muted);">Mioyo iliyojitolea kukuza elimu hii tukufu</span>
                    </div>
                </div>

                <div class="support-wall-grid">
                    @foreach ($recentDonations as $donation)
                        <div class="wall-card">
                            <div class="wall-card-top">
                                <span class="wall-donor-name">{{ $donation->donor_name ?? 'Mchangiaji' }}</span>
                                <span class="wall-amount">{{ $donation->formattedAmount() }}</span>
                            </div>
                            @if ($donation->message)
                                <p class="wall-prayer">"{{ $donation->message }}"</p>
                            @else
                                <p class="wall-prayer">"Jazaakallahu Khayran kwa kuunga mkono maktaba ya Hadithi."</p>
                            @endif
                            <span class="wall-time">{{ $donation->completed_at ? $donation->completed_at->diffForHumans() : 'Hivi karibuni' }}</span>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        {{-- Maswali Yanayoulizwa Mara kwa Mara (FAQ) --}}
        <section class="support-faq" aria-label="Maswali ya Kawaida">
            <h3 class="faq-title">Maswali Yanayoulizwa Mara kwa Mara</h3>

            <div class="faq-grid">
                <div class="faq-item">
                    <h4 class="faq-q">Je, naweza kutoa mchango bila jina langu kuonekana?</h4>
                    <p class="faq-a">Ndiyo. Weka alama kwenye kisanduku cha "Changia bila kutaja jina" na mchango wako hautajumuisha jina lako hadharani.</p>
                </div>

                <div class="faq-item">
                    <h4 class="faq-q">Je, nikiwa nje ya Tanzania naweza kulipa?</h4>
                    <p class="faq-a">Ndiyo. Mfumo unakubali kadi za kimataifa za Visa na Mastercard kwa sarafu ya Dola (USD) au sarafu ya nchi yako.</p>
                </div>

                <div class="faq-item">
                    <h4 class="faq-q">Je, fedha hizi zinatumika kwa ajili gani?</h4>
                    <p class="faq-a">Fedha zote zinatumika kugharamia seva za mtandao, leseni za sauti za hadithi, na utayarishaji wa maudhui ya Kiswahili.</p>
                </div>

                <div class="faq-item">
                    <h4 class="faq-q">Je, kusoma Hadithi na Qur'ani kutaendelea kuwa bure?</h4>
                    <p class="faq-a">Ndiyo, 100%. Maudhui yote ya msingi (Hadithi 1,000, Dua 2,000 na Qur'ani) yatabaki bure milele kwa kila muislamu.</p>
                </div>
            </div>
        </section>
    </div>
</div>
