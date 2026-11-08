/* ==========================================================================
   BAYT UL HADITH - PRODUCTION PWA SERVICE WORKER (OFFLINE + PUSH)
   ========================================================================== */

const CACHE_STATIC = 'bayt-static-v4';
const CACHE_DYNAMIC = 'bayt-dynamic-v4';
const OFFLINE_URL = '/offline.html';

const PRECACHE_ASSETS = [
    '/',
    '/offline.html',
    '/manifest.webmanifest',
    '/css/gold-black.css',
    '/css/app.css',
    '/js/reader.js',
    '/js/i18n-pages.js',
    '/js/pwa.js',
    '/icons/icon-192.png',
    '/icons/icon-512.png',
    '/images/logo.png',
    '/images/logo-gold.png'
];

/* --------------------------------------------------------------------------
   1. Install: Pre-cache Essential App Shell
   -------------------------------------------------------------------------- */
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_STATIC).then((cache) => {
            return Promise.allSettled(
                PRECACHE_ASSETS.map((asset) => cache.add(new Request(asset, { cache: 'reload' })))
            );
        }).then(() => self.skipWaiting())
    );
});

/* --------------------------------------------------------------------------
   2. Activate: Clean up old caches & take control immediately
   -------------------------------------------------------------------------- */
self.addEventListener('activate', (event) => {
    event.waitUntil(
        (async () => {
            const keys = await caches.keys();
            await Promise.all(
                keys.map((key) => {
                    if (key !== CACHE_STATIC && key !== CACHE_DYNAMIC) {
                        return caches.delete(key);
                    }
                })
            );
            await self.clients.claim();
        })()
    );
});

/* --------------------------------------------------------------------------
   3. Fetch: Smart Caching Strategies
   -------------------------------------------------------------------------- */
self.addEventListener('fetch', (event) => {
    const { request } = event;
    const url = new URL(request.url);

    // Never intercept non-GET requests
    if (request.method !== 'GET') return;

    // Do not cache Livewire polling, internal POSTs, or Admin area
    if (
        url.pathname.startsWith('/livewire') ||
        url.pathname.startsWith('/admin') ||
        url.pathname.startsWith('/api/push') ||
        request.headers.get('X-Livewire')
    ) {
        return;
    }

    // A. Static Assets (CSS, JS, Fonts, Images, Icons) -> Stale-While-Revalidate
    const isStaticAsset = (
        url.pathname.match(/\.(css|js|png|jpg|jpeg|svg|webp|woff2|woff|ttf|ico)$/i) ||
        url.pathname.startsWith('/icons/') ||
        url.pathname.startsWith('/css/') ||
        url.pathname.startsWith('/js/')
    );

    if (isStaticAsset) {
        event.respondWith(
            caches.open(CACHE_STATIC).then(async (cache) => {
                const cachedResponse = await cache.match(request);
                const fetchPromise = fetch(request).then((networkResponse) => {
                    if (networkResponse && networkResponse.status === 200) {
                        cache.put(request, networkResponse.clone());
                    }
                    return networkResponse;
                }).catch(() => null);

                return cachedResponse || fetchPromise;
            })
        );
        return;
    }

    // B. Page Navigation (HTML) -> Network-First with Dynamic Cache Fallback
    if (request.mode === 'navigate') {
        event.respondWith(
            (async () => {
                try {
                    const networkResponse = await fetch(request);
                    if (networkResponse && networkResponse.status === 200) {
                        const cache = await caches.open(CACHE_DYNAMIC);
                        cache.put(request, networkResponse.clone());
                    }
                    return networkResponse;
                } catch (_) {
                    // Try to match the page from dynamic or static cache (previously visited hadiths)
                    const cachedResponse = await caches.match(request);
                    if (cachedResponse) {
                        return cachedResponse;
                    }
                    // Fallback to offline page
                    const offlinePage = await caches.match(OFFLINE_URL);
                    return offlinePage || new Response('Hakuna mtandao. Tafadhali unganisha mtandao kusoma hadith.', {
                        status: 503,
                        headers: { 'Content-Type': 'text/plain; charset=utf-8' }
                    });
                }
            })()
        );
    }
});

/* --------------------------------------------------------------------------
   4. Arifa za Kila Siku (Daily Push Notifications)
   -------------------------------------------------------------------------- */
self.addEventListener('push', (event) => {
    let payload = {
        title: 'Hadithi ya Siku · Bayt Ul Hadith',
        body: 'Soma Hadithi ya leo upate mafundisho sahihi ya Mtume Muhammad ﷺ.',
        url: '/hadith-ya-leo',
        icon: '/icons/icon-192.png',
        badge: '/icons/icon-192.png',
        tag: 'daily-hadith'
    };

    if (event.data) {
        try {
            const data = event.data.json();
            payload = Object.assign(payload, data);
        } catch (_) {
            payload.body = event.data.text() || payload.body;
        }
    }

    const options = {
        body: payload.body,
        icon: payload.icon || '/icons/icon-192.png',
        badge: payload.badge || '/icons/icon-192.png',
        data: { url: payload.url || '/hadith-ya-leo' },
        tag: payload.tag || 'daily-hadith',
        vibrate: [120, 60, 120],
        renotify: true
    };

    event.waitUntil(self.registration.showNotification(payload.title, options));
});

self.addEventListener('notificationclick', (event) => {
    event.notification.close();
    const targetUrl = (event.notification.data && event.notification.data.url) ? event.notification.data.url : '/hadith-ya-leo';

    event.waitUntil(
        clients.matchAll({ type: 'window', includeUncontrolled: true }).then((windowClients) => {
            for (let client of windowClients) {
                if (client.url.includes(targetUrl) && 'focus' in client) {
                    return client.focus();
                }
            }
            if (clients.openWindow) {
                return clients.openWindow(targetUrl);
            }
        })
    );
});