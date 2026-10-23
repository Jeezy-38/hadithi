<div id="pwa-install-banner" class="pwa-install-banner" role="dialog" aria-labelledby="pwa-title" aria-modal="true" hidden>
    <div class="pwa-banner-overlay" id="pwa-banner-overlay"></div>
    <div class="pwa-banner-card">
        <button type="button" class="pwa-banner-close" id="pwa-close-btn" aria-label="Funga">
            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>

        <div class="pwa-banner-body">
            <img src="{{ asset('images/logo.png') }}" alt="" class="pwa-banner-logo" width="40" height="40">
            <div class="pwa-banner-content">
                <h3 id="pwa-title">Hadith App</h3>
                <p class="pwa-banner-desc">Inafunguka bila intaneti &bull; Usomaji wa Haraka</p>
            </div>
        </div>

        {{-- Vitufe vya Kawaida vya Android / Chrome / Desktop --}}
        <div class="pwa-banner-actions" id="pwa-standard-actions">
            <button type="button" id="pwa-install-action-btn" class="pwa-btn-primary">
                <span>Install App Now</span>
            </button>
            <button type="button" id="pwa-dismiss-btn" class="pwa-btn-secondary">
                <span>Baadaye</span>
            </button>
        </div>

        {{-- Maelekezo Maalum ya iOS (iPhone Safari) --}}
        <div class="pwa-ios-instructions" id="pwa-ios-instructions" hidden>
            <p class="pwa-ios-hint">Gusa kitufe cha <strong>Kushiriki</strong> <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" style="display:inline-block;vertical-align:middle;margin:0 2px;"><path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"/><polyline points="16 6 12 2 8 6"/><line x1="12" y1="2" x2="12" y2="15"/></svg> kisha chagua <strong>"Add to Home Screen"</strong>.</p>
        </div>
    </div>
</div>
