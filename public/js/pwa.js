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
            document.referrer.includes('android-app://') ||
            localStorage.getItem('hadith_pwa_installed') === '1';
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
        if (standalone() && !force) return;
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
        }, 320);
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

        // Pop up mara moja ikiwa bado haijaonekana
        if (!isDismissed()) {
            showBanner();
        }
    });

    // 2. Install Button Action
    if (installBtn) {
        installBtn.addEventListener('click', async () => {
            if (deferredPrompt) {
                const prompt = deferredPrompt;
                deferredPrompt = null;
                try {
                    await prompt.prompt();
                    const choice = await prompt.userChoice;
                    if (choice && choice.outcome === 'accepted') {
                        try {
                            localStorage.setItem('hadith_pwa_installed', '1');
                        } catch (_) {}
                        hideBanner();
                    }
                } catch (error) {
                    console.warn('PWA prompt error:', error);
                    hideBanner();
                }
                return;
            }

            // Ikiwa hakuna native prompt (mfano Safari ya iOS au browser nyingine)
            if (isIos() && iosInstructions) {
                if (stdActions) stdActions.hidden = true;
                iosInstructions.hidden = false;
            } else {
                // Browser ya kawaida: elekeza mtumiaji kuongeza kwenye home screen
                alert("Ili kuweka Hadith App kwenye simu yako:\n1. Gusa menyu ya kivinjari chako (vitone 3 ⋮ juu au chini)\n2. Chagua 'Install app' au 'Ongeza kwenye Skrini ya Mwanzo' (Add to Home Screen).");
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

    // 6. Pop up ya Kwanza na Floating Back-to-Top
    const initPageUi = () => {
        // A) Floating Back to Top Button
        const backToTopBtn = document.getElementById('back-to-top');
        if (backToTopBtn) {
            let scrollTicking = false;
            window.addEventListener('scroll', () => {
                if (!scrollTicking) {
                    window.requestAnimationFrame(() => {
                        if (window.scrollY > 280) {
                            backToTopBtn.classList.add('is-visible');
                        } else {
                            backToTopBtn.classList.remove('is-visible');
                        }
                        scrollTicking = false;
                    });
                    scrollTicking = true;
                }
            }, { passive: true });

            backToTopBtn.addEventListener('click', () => {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        }

        // B) PWA First-Visit Modern Pop-up
        if (!standalone() && !isDismissed()) {
            setTimeout(() => {
                showBanner();
            }, 1100);
        }
    };

    if (document.readyState === 'loading') {
        window.addEventListener('DOMContentLoaded', initPageUi);
    } else {
        initPageUi();
    }

    // 7. Service Worker Registration
    if ('serviceWorker' in navigator && window.isSecureContext) {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register(new URL('sw.js', base), { scope: base.pathname, updateViaCache: 'none' })
                .catch((error) => console.warn('Hadith offline setup failed.', error));
        });
    }
})();
