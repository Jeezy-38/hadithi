(() => {
    const script = document.currentScript;
    const base = new URL('../', script ? script.src : window.location.href);

    const banner = document.getElementById('pwa-install-banner');
    const overlay = document.getElementById('pwa-banner-overlay');
    const closeBtn = document.getElementById('pwa-close-btn');
    const installBtn = document.getElementById('pwa-install-action-btn');
    const dismissBtn = document.getElementById('pwa-dismiss-btn');
    const iosDismissBtn = document.getElementById('pwa-ios-dismiss-btn');
    const stdActions = document.getElementById('pwa-standard-actions');
    const iosInstructions = document.getElementById('pwa-ios-instructions');
    const legacyBtn = document.getElementById('pwa-install');

    const standalone = () => {
        return window.matchMedia('(display-mode: standalone)').matches ||
            navigator.standalone ||
            document.referrer.includes('android-app://');
    };

    const isIos = () => {
        return /iPad|iPhone|iPod/.test(navigator.userAgent) ||
            (navigator.platform === 'MacIntel' && navigator.maxTouchPoints > 1);
    };

    let deferredPrompt = null;

    const isDismissed = () => {
        try {
            return sessionStorage.getItem('hadith_pwa_dismissed') === '1';
        } catch (_) {
            return false;
        }
    };

    const markDismissed = () => {
        try {
            sessionStorage.setItem('hadith_pwa_dismissed', '1');
        } catch (_) {}
    };

    const showBanner = (force = false) => {
        if (!banner) return;
        if (standalone()) return;
        if (!force && isDismissed()) return;

        if (isIos() && !deferredPrompt) {
            if (stdActions) stdActions.hidden = true;
            if (iosInstructions) iosInstructions.hidden = false;
        } else {
            if (stdActions) stdActions.hidden = false;
            if (iosInstructions) iosInstructions.hidden = true;
        }

        banner.hidden = false;
        requestAnimationFrame(() => {
            banner.classList.add('is-visible');
        });
    };

    const hideBanner = () => {
        if (!banner) return;
        banner.classList.remove('is-visible');
        setTimeout(() => {
            banner.hidden = true;
        }, 300);
        markDismissed();
    };

    // 1. Android / Chrome / Edge Native Prompt Capture
    window.addEventListener('beforeinstallprompt', (event) => {
        if (standalone()) return;
        event.preventDefault();
        deferredPrompt = event;

        if (legacyBtn) {
            legacyBtn.hidden = false;
            legacyBtn.style.removeProperty('display');
        }

        // Pop up mara tu unapoingia
        showBanner();
    });

    // 2. Install Button Action
    if (installBtn) {
        installBtn.addEventListener('click', async () => {
            if (!deferredPrompt) {
                // Ikiwa hakuna deferredPrompt (mfano desktop au browser isiyo na native prompt), elekeza au funga
                hideBanner();
                return;
            }
            const prompt = deferredPrompt;
            deferredPrompt = null;
            try {
                await prompt.prompt();
                const choice = await prompt.userChoice;
                if (choice.outcome === 'accepted') {
                    hideBanner();
                }
            } catch (error) {
                console.warn('PWA install error:', error);
                hideBanner();
            }
        });
    }

    // 3. Dismiss Actions
    if (dismissBtn) dismissBtn.addEventListener('click', hideBanner);
    if (closeBtn) closeBtn.addEventListener('click', hideBanner);
    if (overlay) overlay.addEventListener('click', hideBanner);
    if (iosDismissBtn) iosDismissBtn.addEventListener('click', hideBanner);

    // 4. Legacy button support
    if (legacyBtn) {
        legacyBtn.addEventListener('click', () => {
            showBanner(true);
        });
    }

    // 5. App Installed Event
    window.addEventListener('appinstalled', () => {
        hideBanner();
        try {
            localStorage.setItem('hadith_pwa_installed', '1');
        } catch (_) {}
    });

    // 6. Kwenye iOS Safari (ambayo haina beforeinstallprompt), pop up mara tu baada ya kupakia
    window.addEventListener('DOMContentLoaded', () => {
        if (isIos() && !standalone() && !isDismissed()) {
            setTimeout(() => {
                showBanner();
            }, 1200);
        }
    });

    // 7. Service Worker Registration
    if ('serviceWorker' in navigator && window.isSecureContext) {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register(new URL('sw.js', base), { scope: base.pathname, updateViaCache: 'none' })
                .catch((error) => console.warn('Hadith offline setup failed.', error));
        });
    }
})();
