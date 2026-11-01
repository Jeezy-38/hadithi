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
    <div class="bayt-splash-body">
        <svg class="bayt-splash-calli" viewBox="0 0 300 130" aria-hidden="true"><text x="150" y="98" text-anchor="middle" direction="rtl">بيت الحديث</text></svg>
        <div class="bayt-splash-rule"></div>
        <div class="bayt-splash-title">Bayt Ul <span>Hadith</span></div>
        <div class="bayt-splash-caption" id="bayt-splash-caption">Inapakia…</div>
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

        // Maneno ya kupakia kulingana na lugha ya msomaji
        try {
            const lang = localStorage.getItem('hadith-language') || 'both';
            const cap = document.getElementById('bayt-splash-caption');
            if (cap && lang === 'en') cap.textContent = 'Loading…';
        } catch (_) {}

        const startTime = Date.now();
        const minDisplayMs = 1900;

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
            setTimeout(dismissSplash, 3000);
        }
    })();
</script>
