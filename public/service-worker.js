const ROOT = new URL('./', self.location.href);
// Separate caches for apps installed in different subdirectories on the same host.
const PREFIX = `hadith-pwa-${ROOT.pathname}-`;
const CACHE = `${PREFIX}v2`;
const OFFLINE = new URL('offline.html', ROOT).href;
const ASSETS = ['offline.html', 'icons/icon-192.png', 'icons/icon-512.png', 'icons/apple-touch-icon.png'].map(path => new URL(path, ROOT).href);
self.addEventListener('install', event => {
    event.waitUntil(caches.open(CACHE).then(cache => cache.addAll(ASSETS)));
});
self.addEventListener('message', event => {
    if (event.data?.type === 'SKIP_WAITING') self.skipWaiting();
});
self.addEventListener('activate', event => {
    event.waitUntil((async () => {
        const keys = await caches.keys();
        await Promise.all(keys.filter(key => key.startsWith(PREFIX) && key !== CACHE).map(key => caches.delete(key)));
        await self.clients.claim();
    })());
});
self.addEventListener('fetch', event => {
    const request = event.request;
    const url = new URL(request.url);
    if (request.method !== 'GET' || url.origin !== ROOT.origin || !url.pathname.startsWith(ROOT.pathname)) return;
    // Session HTML, Livewire, bookmarks API and audio must stay on the network.
    if (request.mode === 'navigate') {
        event.respondWith(fetch(request).catch(async () => {
            const cache = await caches.open(CACHE);
            return (await cache.match(OFFLINE)) || Response.error();
        }));
    } else if (ASSETS.includes(url.href)) {
        event.respondWith(caches.open(CACHE).then(async cache => (await cache.match(request)) || fetch(request)));
    }
});
