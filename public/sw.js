/* Bump this version whenever the offline screen changes. */
const CACHE = 'hadith-pwa-v1';
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
