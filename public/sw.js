/* Bump this version whenever the offline screen or assets change. */
const CACHE = 'hadith-pwa-v3';
const OFFLINE = new URL('offline.html', self.registration.scope).href;

self.addEventListener('install', (event) => {
    event.waitUntil(caches.open(CACHE).then((cache) => cache.add(new Request(OFFLINE, { cache: 'reload' }))));
});

self.addEventListener('activate', (event) => {
    event.waitUntil((async () => {
        const keys = await caches.keys();
        await Promise.all(keys.filter((key) => key.startsWith('hadith-pwa-') && key !== CACHE).map((key) => caches.delete(key)));
        await self.clients.claim();
    })());
});

self.addEventListener('fetch', (event) => {
    // Never cache Livewire POSTs, session-bearing HTML, API responses, or audio.
    if (event.request.method !== 'GET' || event.request.mode !== 'navigate' ||
        !event.request.url.startsWith(self.registration.scope)) return;

    event.respondWith((async () => {
        try {
            return await fetch(event.request);
        } catch {
            return (await caches.match(OFFLINE)) || new Response('Hakuna mtandao. Tafadhali jaribu tena.', {
                status: 503, headers: { 'Content-Type': 'text/plain; charset=utf-8' },
            });
        }
    })());
});

/* ==========================================================================
   ARIF ZA KILA SIKU (DAILY PUSH NOTIFICATIONS)
   ========================================================================== */
self.addEventListener('push', (event) => {
    let payload = {
        title: 'Hadithi ya Siku · Bayt Al-Hadith',
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
