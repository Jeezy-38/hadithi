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
    const legacyHelp = document.getElementById('pwa-install-help');
    const drawerPwaBtn = document.getElementById('drawer-pwa-install-btn');
    const drawerPwaTitle = document.getElementById('drawer-pwa-title');
    const drawerPwaSub = document.getElementById('drawer-pwa-sub');
    const drawerPwaBadge = document.getElementById('drawer-pwa-badge');

    const standalone = () => {
        const isStandaloneMedia = Boolean(window.matchMedia && window.matchMedia('(display-mode: standalone)').matches);
        const isNavStandalone = Boolean(navigator.standalone);
        const isAndroidReferrer = Boolean(typeof document.referrer === 'string' && document.referrer.includes('android-app://'));
        let isLocalInstalled = false;
        try {
            isLocalInstalled = typeof localStorage !== 'undefined' && localStorage && localStorage.getItem('hadith_pwa_installed') === '1';
        } catch (_) {}

        return isStandaloneMedia || isNavStandalone || isAndroidReferrer || isLocalInstalled;
    };

    const isIos = () => {
        return /iPad|iPhone|iPod/.test(navigator.userAgent) ||
            (navigator.platform === 'MacIntel' && navigator.maxTouchPoints > 1);
    };

    let deferredPrompt = null;

    const isDismissed = () => {
        try {
            return typeof sessionStorage !== 'undefined' && sessionStorage && sessionStorage.getItem('hadith_pwa_dismissed') === '1';
        } catch (_) {
            return false;
        }
    };

    const markDismissed = () => {
        try {
            if (typeof sessionStorage !== 'undefined' && sessionStorage) {
                sessionStorage.setItem('hadith_pwa_dismissed', '1');
            }
        } catch (_) {}
    };

    const hideLegacy = () => {
        if (legacyBtn) {
            legacyBtn.hidden = true;
            legacyBtn.style.display = 'none';
        }
        if (legacyHelp) {
            legacyHelp.hidden = true;
        }
    };

    const updateDrawerPwaUi = () => {
        if (!drawerPwaBtn) return;
        if (standalone()) {
            if (drawerPwaTitle) drawerPwaTitle.textContent = 'App Imesakinishwa';
            if (drawerPwaSub) drawerPwaSub.textContent = 'Tayari ipo kwenye kifaa chako';
            if (drawerPwaBadge) {
                drawerPwaBadge.textContent = 'Imewekwa';
                drawerPwaBadge.classList.add('is-installed');
            }
        } else {
            if (drawerPwaTitle) drawerPwaTitle.textContent = 'Install App Now';
            if (drawerPwaSub) drawerPwaSub.textContent = 'Download App Now';
            if (drawerPwaBadge) {
                drawerPwaBadge.textContent = 'App';
                drawerPwaBadge.classList.remove('is-installed');
            }
        }
    };

    const showBanner = (force = false) => {
        if (!banner) return;
        if (standalone() && !force) return;
        if (!force && isDismissed()) return;

        if (stdActions) stdActions.hidden = false;
        if (iosInstructions) iosInstructions.hidden = true;

        banner.hidden = false;
        if (typeof requestAnimationFrame === 'function') {
            requestAnimationFrame(() => {
                banner.classList.add('is-visible');
            });
        } else {
            banner.classList.add('is-visible');
        }
    };

    const hideBanner = () => {
        hideLegacy();
        if (banner) {
            banner.classList.remove('is-visible');
            if (typeof setTimeout === 'function') {
                setTimeout(() => {
                    banner.hidden = true;
                }, 320);
            } else {
                banner.hidden = true;
            }
        }
        markDismissed();
    };

    const triggerPrompt = async () => {
        if (!deferredPrompt) return;
        const prompt = deferredPrompt;
        deferredPrompt = null;
        hideLegacy();
        hideBanner();
        try {
            await prompt.prompt();
            const choice = await prompt.userChoice;
            if (choice && choice.outcome === 'accepted') {
                try {
                    if (typeof localStorage !== 'undefined' && localStorage) {
                        localStorage.setItem('hadith_pwa_installed', '1');
                    }
                } catch (_) {}
            }
        } catch (error) {
            console.warn('Hadith installation was unavailable.', error);
        }
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

        updateDrawerPwaUi();

        if (!isDismissed()) {
            showBanner();
        }
    });

    // 2. Install Button Actions
    if (drawerPwaBtn) {
        drawerPwaBtn.addEventListener('click', async () => {
            if (standalone()) {
                alert("Hadith App tayari imewekwa kwenye kifaa hiki!\n\nUnaweza kuifungua moja kwa moja kutoka kwenye Skrini ya Programu (Applications), au bofya alama ya 'Open in app' kwenye mwambaa wa anwani wa kivinjari chako.");
                return;
            }

            if (deferredPrompt) {
                await triggerPrompt();
                return;
            }

            if (isIos() && iosInstructions) {
                showBanner(true);
            } else {
                showBanner(true);
            }
        });
    }

    if (installBtn) {
        installBtn.addEventListener('click', async () => {
            if (deferredPrompt) {
                await triggerPrompt();
                return;
            }

            if (isIos()) {
                if (navigator.share) {
                    try {
                        await navigator.share({
                            title: 'Hadith App',
                            url: window.location.href,
                        });
                        hideBanner();
                        return;
                    } catch (_) {}
                }
                alert("Ili kuweka Hadith App kwenye iPhone/iPad:\n1. Gusa kitufe cha Kushiriki (Share icon chini ya Safari)\n2. Chagua 'Add to Home Screen'.");
                hideBanner();
            } else {
                alert("Ili kuweka Hadith App kwenye simu yako:\n1. Gusa menyu ya kivinjari chako (vitone 3 ⋮ juu au chini)\n2. Chagua 'Install app' au 'Ongeza kwenye Skrini ya Mwanzo' (Add to Home Screen).");
                hideBanner();
            }
        });
    }

    if (legacyBtn) {
        legacyBtn.addEventListener('click', async () => {
            if (deferredPrompt) {
                await triggerPrompt();
            } else {
                showBanner(true);
            }
        });
    }

    // 3. Dismiss Actions
    if (dismissBtn) dismissBtn.addEventListener('click', hideBanner);
    if (closeBtn) closeBtn.addEventListener('click', hideBanner);
    if (overlay) overlay.addEventListener('click', hideBanner);
    if (iosDismissBtn) iosDismissBtn.addEventListener('click', hideBanner);

    // 4. App Installed Event
    window.addEventListener('appinstalled', () => {
        hideLegacy();
        hideBanner();
        try {
            if (typeof localStorage !== 'undefined' && localStorage) {
                localStorage.setItem('hadith_pwa_installed', '1');
            }
        } catch (_) {}
        updateDrawerPwaUi();
    });

    // 5. iOS Help initialization
    if (!standalone() && isIos() && legacyHelp) {
        legacyHelp.hidden = false;
    }

    // 6. Pop up ya Kwanza na Floating Back-to-Top
    const initPageUi = () => {
        updateDrawerPwaUi();
        const backToTopBtn = document.getElementById('back-to-top');
        if (backToTopBtn) {
            let scrollTicking = false;
            window.addEventListener('scroll', () => {
                if (!scrollTicking) {
                    const raf = typeof requestAnimationFrame === 'function' ? requestAnimationFrame : (fn) => fn();
                    raf(() => {
                        if (typeof window.scrollY === 'number' && window.scrollY > 280) {
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
                if (typeof window.scrollTo === 'function') {
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }
            });
        }

        if (!standalone() && !isDismissed()) {
            if (typeof setTimeout === 'function') {
                setTimeout(() => {
                    showBanner();
                }, 1100);
            }
        }
    };

    if (typeof document.readyState === 'string' && document.readyState === 'loading') {
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
