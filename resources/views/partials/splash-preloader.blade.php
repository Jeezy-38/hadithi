<div id="bayt-splash-loader" class="bayt-splash-screen" aria-hidden="true">
    <div class="bayt-splash-glow"></div>
    <div class="bayt-splash-body">
        <div class="bayt-splash-logo-wrap">
            <img src="{{ asset('images/logo.png') }}" alt="Bayt Al-Hadith" class="bayt-splash-logo">
        </div>
        <div class="bayt-splash-title">
            Bayt Al-<span>Hadith</span>
        </div>
        <div class="bayt-splash-sub">
            Maktaba ya Hadithi za Mtume ﷺ
        </div>
        <div class="bayt-splash-spinner-wrap">
            <div class="bayt-splash-spinner"></div>
            <span class="bayt-splash-caption">Inapakia hazina ya mafundisho...</span>
        </div>
    </div>
</div>

<script>
    (function () {
        const splash = document.getElementById('bayt-splash-loader');
        if (!splash) return;

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
