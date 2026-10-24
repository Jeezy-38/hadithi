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

    // 8. Arifa za Kila Siku (Daily Push Notifications Handler - Default: ON)
    const notifBtn = document.getElementById('drawer-notif-btn');
    const notifTitle = document.getElementById('drawer-notif-title');
    const notifSub = document.getElementById('drawer-notif-sub');
    const notifBadge = document.getElementById('drawer-notif-badge');

    const isNotificationOptedOut = () => {
        try {
            return localStorage.getItem('hadith_daily_notif_enabled') === '0';
        } catch (_) {
            return false;
        }
    };

    const updateNotificationUi = () => {
        if (!notifBtn || !('Notification' in window)) return;
        const permission = Notification.permission;
        const optedOut = isNotificationOptedOut();

        if (permission === 'granted' && !optedOut) {
            if (notifBadge) {
                notifBadge.textContent = 'Imewashwa';
                notifBadge.classList.add('is-installed');
            }
            if (notifSub) {
                notifSub.textContent = 'Unapokea Hadithi kila asubuhi';
            }
        } else {
            if (notifBadge) {
                notifBadge.textContent = 'Washa';
                notifBadge.classList.remove('is-installed');
            }
            if (notifSub) {
                notifSub.textContent = optedOut ? 'Arifa zimezimwa kwa hiari' : 'Hadithi ya siku kila asubuhi';
            }
        }
    };

    const getStoredEndpoint = () => {
        try {
            let ep = localStorage.getItem('bayt_push_endpoint');
            if (!ep) {
                ep = 'local-client-' + Math.random().toString(36).substring(2) + '-' + Date.now();
                localStorage.setItem('bayt_push_endpoint', ep);
            }
            return ep;
        } catch (_) {
            return 'local-client-' + Date.now();
        }
    };

    const syncPushSubscription = async () => {
        if (isNotificationOptedOut()) return null;
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        let endpoint = getStoredEndpoint();

        if ('serviceWorker' in navigator) {
            try {
                const reg = await navigator.serviceWorker.ready;
                if (reg.pushManager) {
                    const sub = await reg.pushManager.getSubscription();
                    if (sub && sub.endpoint) {
                        endpoint = sub.endpoint;
                        try {
                            localStorage.setItem('bayt_push_endpoint', endpoint);
                        } catch (_) {}
                    }
                }
            } catch (_) {}
        }

        try {
            await fetch('/api/push-subscriptions', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken || ''
                },
                body: JSON.stringify({ endpoint: endpoint })
            });
        } catch (_) {}

        return endpoint;
    };

    const checkAndDeliverDailyHadith = async () => {
        if (isNotificationOptedOut() || !('Notification' in window) || Notification.permission !== 'granted') {
            return;
        }

        const todayDate = new Date().toISOString().slice(0, 10);
        const dailyShownKey = 'bayt_daily_notified_' + todayDate;
        try {
            if (localStorage.getItem(dailyShownKey) === '1') {
                return; // Tayari imeonyeshwa leo
            }
        } catch (_) {}

        try {
            const res = await fetch('/api/hadith-ya-leo');
            if (!res.ok) return;
            const data = await res.json();

            if ('serviceWorker' in navigator) {
                const reg = await navigator.serviceWorker.ready;
                reg.showNotification(data.title || 'Hadithi ya Siku · Bayt Al-Hadith', {
                    body: data.body || 'Karibu Bayt Al-Hadith kusoma Sunnah za Mtume ﷺ.',
                    icon: data.icon || '/icons/icon-192.png',
                    badge: data.badge || '/icons/icon-192.png',
                    data: { url: data.url || '/hadith-ya-leo' },
                    tag: 'daily-hadith-' + todayDate
                });
            } else {
                new Notification(data.title || 'Hadithi ya Siku · Bayt Al-Hadith', {
                    body: data.body || 'Karibu Bayt Al-Hadith kusoma Sunnah za Mtume ﷺ.',
                    icon: '/icons/icon-192.png'
                });
            }

            try {
                localStorage.setItem(dailyShownKey, '1');
            } catch (_) {}
        } catch (e) {
            console.warn('Hitilafu ya kupokea Hadithi ya Siku:', e);
        }
    };

    const initDefaultDailyNotifications = async () => {
        if (!('Notification' in window)) return;
        if (isNotificationOptedOut()) {
            updateNotificationUi();
            return;
        }

        if (Notification.permission === 'granted') {
            updateNotificationUi();
            await syncPushSubscription();
            await checkAndDeliverDailyHadith();
        } else if (Notification.permission === 'default') {
            try {
                const perm = await Notification.requestPermission();
                if (perm === 'granted') {
                    updateNotificationUi();
                    await syncPushSubscription();
                    await checkAndDeliverDailyHadith();
                } else {
                    updateNotificationUi();
                }
            } catch (_) {
                updateNotificationUi();
            }
        } else {
            updateNotificationUi();
        }
    };

    // Anzisha mara moja baada ya ukurasa kupakia
    if ('Notification' in window) {
        updateNotificationUi();
        setTimeout(initDefaultDailyNotifications, 1800);
        // Backup: Ukigusa popote (user gesture) iwapo browser inahitaji click
        window.addEventListener('click', initDefaultDailyNotifications, { once: true });
    }

    // Toggle ya mtumiaji kwenye menyu ya pembeni (Drawer)
    if (notifBtn) {
        notifBtn.addEventListener('click', async (e) => {
            e.stopPropagation();
            if (!('Notification' in window)) {
                alert('Kifaa hiki au kivinjari hakiruhusu arifa za PWA.');
                return;
            }

            const currentlyActive = Notification.permission === 'granted' && !isNotificationOptedOut();

            if (currentlyActive) {
                // Mtumiaji ameamua kuzima kwa hiari
                try {
                    localStorage.setItem('hadith_daily_notif_enabled', '0');
                } catch (_) {}
                updateNotificationUi();

                // Notifisha seva kuzima subscription
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                const endpoint = getStoredEndpoint();
                fetch('/api/push-subscriptions', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken || ''
                    },
                    body: JSON.stringify({ endpoint: endpoint })
                }).catch(() => {});
                return;
            }

            // Mtumiaji anawasha arifa
            try {
                localStorage.removeItem('hadith_daily_notif_enabled');
                localStorage.setItem('hadith_daily_notif_enabled', '1');
            } catch (_) {}

            try {
                const permission = await Notification.requestPermission();
                if (permission === 'granted') {
                    updateNotificationUi();
                    await syncPushSubscription();

                    // Onyesha arifa ya kwanza ya uthibitisho papo hapo
                    if ('serviceWorker' in navigator) {
                        const reg = await navigator.serviceWorker.ready;
                        reg.showNotification('Hadithi ya Siku · Bayt Al-Hadith', {
                            body: 'Arifa zimewashwa kikamilifu! Utapokea Hadithi ya Siku kila asubuhi.',
                            icon: '/icons/icon-192.png',
                            badge: '/icons/icon-192.png',
                            data: { url: '/hadith-ya-leo' },
                            tag: 'welcome-daily-hadith'
                        });
                    }
                } else {
                    updateNotificationUi();
                }
            } catch (err) {
                console.warn('Hitilafu ya arifa:', err);
                updateNotificationUi();
            }
        });
    }
})();
