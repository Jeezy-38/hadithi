const { test } = require('node:test');
const assert = require('node:assert/strict');
const fs = require('node:fs');
const vm = require('node:vm');

function createUi({ standalone = false, secure = true, userAgent = '', maxTouchPoints = 0, platform = 'MacIntel' } = {}) {
    const events = {};
    const nodes = {
        'pwa-install': { hidden: true, style: { display: '', removeProperty(p) { delete this[p]; } }, events: {}, addEventListener(t, f) { this.events[t] = f; } },
        'pwa-install-help': { hidden: true, events: {}, addEventListener(t, f) { this.events[t] = f; } },
    };

    let swRegistered = null;
    const navigator = {
        userAgent,
        platform,
        maxTouchPoints,
        standalone,
        serviceWorker: {
            register: async (url, opts) => {
                swRegistered = { url: url.href, opts };
                return swRegistered;
            },
        },
    };

    const window = {
        isSecureContext: secure,
        matchMedia: (query) => ({
            matches: query.includes('display-mode: standalone') ? standalone : false,
            addEventListener: () => {},
        }),
        addEventListener: (t, f) => { events[t] = f; },
    };

    const document = {
        currentScript: { src: 'https://example.test/app/js/pwa.js' },
        getElementById: (id) => nodes[id] || null,
    };

    vm.runInNewContext(fs.readFileSync('public/js/pwa.js', 'utf8'), {
        window,
        navigator,
        document,
        URL,
        console,
    });

    return { events, nodes, navigator, window, getSwRegistered: () => swRegistered };
}

test('beforeinstallprompt reveals install button when not standalone', () => {
    const { events, nodes } = createUi();
    assert.equal(nodes['pwa-install'].hidden, true);

    let prevented = false;
    events.beforeinstallprompt({ preventDefault: () => { prevented = true; } });
    assert.equal(prevented, true);
    assert.equal(nodes['pwa-install'].hidden, false);
});

test('clicking install triggers native prompt and hides button', async () => {
    const { events, nodes } = createUi();
    let promptCalled = 0;
    events.beforeinstallprompt({
        preventDefault: () => {},
        prompt: async () => { promptCalled++; },
        userChoice: Promise.resolve({ outcome: 'accepted' }),
    });

    assert.equal(nodes['pwa-install'].hidden, false);
    await nodes['pwa-install'].events.click();
    assert.equal(promptCalled, 1);
    assert.equal(nodes['pwa-install'].hidden, true);
});

test('appinstalled event hides install button', () => {
    const { events, nodes } = createUi();
    events.beforeinstallprompt({ preventDefault: () => {} });
    assert.equal(nodes['pwa-install'].hidden, false);

    events.appinstalled();
    assert.equal(nodes['pwa-install'].hidden, true);
});

test('iOS safari shows install help instructions', () => {
    const { nodes } = createUi({ userAgent: 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_0 like Mac OS X)' });
    assert.equal(nodes['pwa-install-help'].hidden, false);
});

test('standalone app does not show install button or iOS help', () => {
    const { events, nodes } = createUi({ standalone: true, userAgent: 'iPhone' });
    assert.equal(nodes['pwa-install-help'].hidden, true);
    assert.equal(nodes['pwa-install'].hidden, true);
    if (events.beforeinstallprompt) {
        events.beforeinstallprompt({ preventDefault: () => {} });
        assert.equal(nodes['pwa-install'].hidden, true);
    }
});

test('service worker registers on window load in secure context', async () => {
    const { events, getSwRegistered } = createUi({ secure: true });
    assert.ok(events.load);
    events.load();
    const reg = getSwRegistered();
    assert.ok(reg);
    assert.match(reg.url, /sw\.js$/);
    assert.equal(reg.opts.updateViaCache, 'none');
});

test('sw.js handles offline navigation fallback and ignores POST or audio', async () => {
    const events = {};
    const fallbackResponse = new Response('offline html content', { headers: { 'Content-Type': 'text/html' } });
    const mockCache = {
        add: async () => {},
        match: async () => fallbackResponse,
    };
    const caches = {
        match: async () => fallbackResponse,
        open: async () => mockCache,
        keys: async () => ['hadith-pwa-v1'],
    };
    const self = {
        registration: { scope: 'https://example.test/app/' },
        clients: { claim: async () => {} },
        addEventListener: (t, f) => { events[t] = f; },
    };

    vm.runInNewContext(fs.readFileSync('public/sw.js', 'utf8'), {
        self,
        caches,
        URL,
        Request,
        Response,
        fetch: async () => { throw new Error('Offline'); },
    });

    assert.ok(events.fetch);

    // 1. Navigation request offline gets cached fallback
    let handledResponse = null;
    events.fetch({
        request: { method: 'GET', mode: 'navigate', url: 'https://example.test/app/hadith/1' },
        respondWith: (resPromise) => { handledResponse = resPromise; },
    });
    assert.ok(handledResponse);
    const resolved = await handledResponse;
    assert.equal(await resolved.text(), 'offline html content');

    // 2. POST request, audio endpoint, or cross-scope URL are NOT intercepted
    for (const [method, url, mode] of [
        ['POST', 'https://example.test/app/livewire/update', 'cors'],
        ['GET', 'https://example.test/app/audio/hadith/1/sw', 'cors'],
        ['GET', 'https://example.test/another/url', 'navigate'],
    ]) {
        let intercepted = false;
        events.fetch({
            request: { method, url, mode },
            respondWith: () => { intercepted = true; },
        });
        assert.equal(intercepted, false, `Expected ${method} ${url} not to be intercepted`);
    }
});
