<div id="pwa-install-banner" class="pwa-install-banner" role="dialog" aria-labelledby="pwa-title" aria-modal="true" hidden>
    <div class="pwa-banner-overlay" id="pwa-banner-overlay"></div>
    <div class="pwa-banner-card">
        <button type="button" class="pwa-banner-close" id="pwa-close-btn" aria-label="Funga taarifa ya usakinishaji">
            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>

        <div class="pwa-banner-body">
            <img src="{{ asset('images/logo.png') }}" alt="Hadith Logo" class="pwa-banner-logo">
            <div class="pwa-banner-content">
                <div class="pwa-badge-row">
                    <span class="pwa-banner-badge">APP RASMI</span>
                    <span class="pwa-feature-pill">Hakuna Matangazo</span>
                </div>
                <h3 id="pwa-title">Sakinisha Hadith App</h3>
                <p class="pwa-banner-desc">Pata urahisi wa kusoma Hadith, Qur'ani na Dua moja kwa moja kwenye simu yako kama application halisi.</p>
                <div class="pwa-perks-list">
                    <div class="pwa-perk-item">
                        <span class="pwa-perk-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" width="15" height="15" fill="currentColor">
                                <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
                            </svg>
                        </span>
                        <span>Inafunguka papo hapo hata bila bando (Offline)</span>
                    </div>
                    <div class="pwa-perk-item">
                        <span class="pwa-perk-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="9"></circle>
                                <circle cx="12" cy="6" r="1.5"></circle>
                                <circle cx="16.5" cy="8.5" r="1.5"></circle>
                                <circle cx="17.5" cy="14" r="1.5"></circle>
                                <circle cx="13.5" cy="17.5" r="1.5"></circle>
                                <circle cx="8" cy="16.5" r="1.5"></circle>
                                <circle cx="6.5" cy="11" r="1.5"></circle>
                                <circle cx="8.5" cy="6.5" r="1.5"></circle>
                            </svg>
                        </span>
                        <span>Maktaba, Qur'ani, Dua na Tasbih kiganjani mwako</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Maelekezo ya Kawaida (Android / Chrome / Edge) --}}
        <div class="pwa-banner-actions" id="pwa-standard-actions">
            <button type="button" id="pwa-install-action-btn" class="pwa-btn-primary">
                <svg viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                    <polyline points="7 10 12 15 17 10"></polyline>
                    <line x1="12" y1="15" x2="12" y2="3"></line>
                </svg>
                <span>Sakinisha Sasa</span>
            </button>
            <button type="button" id="pwa-dismiss-btn" class="pwa-btn-secondary">
                <span>Baadaye</span>
            </button>
        </div>

        {{-- Maelekezo Maalum ya iOS (iPhone & iPad Safari) --}}
        <div class="pwa-ios-instructions" id="pwa-ios-instructions" hidden>
            <div class="pwa-ios-guide">
                <div class="pwa-ios-step">
                    <span class="ios-step-num">1</span>
                    <span>Gusa kitufe cha <strong>Kushiriki</strong> (alama ya <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" style="display:inline-block;vertical-align:middle;"><path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"/><polyline points="16 6 12 2 8 6"/><line x1="12" y1="2" x2="12" y2="15"/></svg> chini ya skrini ya Safari).</span>
                </div>
                <div class="pwa-ios-step">
                    <span class="ios-step-num">2</span>
                    <span>Tembeza chini kisha uchague <strong>"Ongeza kwenye Skrini ya Mwanzo"</strong> (Add to Home Screen).</span>
                </div>
            </div>
            <button type="button" id="pwa-ios-dismiss-btn" class="pwa-btn-secondary pwa-btn-full">
                <span>Nimeelewa / Funga</span>
            </button>
        </div>
    </div>
</div>
