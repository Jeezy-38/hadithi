<script>
    (function () {
        try {
            if (sessionStorage.getItem('bayt_splash_seen') === '1') {
                document.write('<style>#bayt-splash-loader{display:none !important;visibility:hidden !important;opacity:0 !important;pointer-events:none !important;}</style>');
            }
        } catch (_) {}
    })();
</script>

<div id="bayt-splash-loader" class="bayt-splash-screen" aria-hidden="true">
    <div class="bayt-splash-glow"></div>
    <div class="bayt-splash-body">
        <div class="bayt-splash-logo-wrap">
            <img src="{{ asset('images/logo.png') }}" alt="Bayt Al-Hadith" class="bayt-splash-logo">
        </div>
        <div class="bayt-splash-title">
            Bayt Al-<span>Hadith</span>
        </div>
        <div class="bayt-splash-spinner-wrap">
            <div class="bayt-splash-spinner">
                <svg class="bayt-spinner-svg" viewBox="0 0 44 44" aria-hidden="true">
                    <circle class="bayt-spinner-track" cx="22" cy="22" r="18" fill="none" stroke-width="2.5"></circle>
                    <circle class="bayt-spinner-head" cx="22" cy="22" r="18" fill="none" stroke-width="2.8" stroke-linecap="round"></circle>
                </svg>
                <div class="bayt-spinner-core"></div>
            </div>
            <span class="bayt-splash-caption">Loading <span class="bayt-splash-dots">...</span></span>
        </div>
    </div>
</div>

<script>
    (function () {
        const splash = document.getElementById('bayt-splash-loader');
        if (!splash) return;

        let hasSeen = false;
        try {
            hasSeen = sessionStorage.getItem('bayt_splash_seen') === '1';
        } catch (_) {}

        if (hasSeen) {
            // Ilishaonekana kwenye session hii — iondoe papo hapo bila kuchelewa
            if (splash.parentNode) {
                splash.parentNode.removeChild(splash);
            }
            return;
        }

        // Mara ya kwanza mtumiaji anafungua app — weka alama kwenye session
        try {
            sessionStorage.setItem('bayt_splash_seen', '1');
        } catch (_) {}

        const startTime = Date.now();
        const minDisplayMs = 1200;

        function dismissSplash() {
            const elapsed = Date.now() - startTime;
            const remaining = Math.max(0, minDisplayMs - elapsed);

            setTimeout(function () {
                splash.classList.add('bayt-splash-hide');
                setTimeout(function () {
                    if (splash && splash.parentNode) {
                        splash.parentNode.removeChild(splash);
                    }
                }, 700);
            }, remaining);
        }

        if (document.readyState === 'complete') {
            dismissSplash();
        } else {
            window.addEventListener('load', dismissSplash, { once: true });
            setTimeout(dismissSplash, 2800);
        }
    })();
</script>
