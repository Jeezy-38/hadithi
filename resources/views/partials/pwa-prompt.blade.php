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
            </div>
        </div>

        {{-- Vitufe vya Kusakinisha --}}
        <div class="pwa-banner-actions" id="pwa-standard-actions">
            <button type="button" id="pwa-install-action-btn" class="pwa-btn-primary">
                <span>Install App Now</span>
            </button>
            <button type="button" id="pwa-dismiss-btn" class="pwa-btn-secondary">
                <span>Later</span>
            </button>
        </div>
    </div>
</div>
