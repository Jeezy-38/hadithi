<x-layouts.app title="Vipendwa vyangu · Hadith, Qur'ani na Dua">
    <div class="reader-shell bookmarks-shell">
        <a class="back-link" href="{{ route('library') }}">
            <span class="back-arrow" aria-hidden="true">←</span>
            <span>Rudi kwenye maktaba</span>
        </a>

        <div class="reader-heading">
            <span class="eyebrow"><span class="eyebrow-dot"></span> VIPENDWA VYANGU</span>
            <h1>Mkusanyiko Uliohifadhiwa</h1>
            @auth
                <p>Hadithi ulizohifadhi zinasawazishwa kwenye akaunti yako. Aya za Qur'ani na Dua zimehifadhiwa salama kwenye kifaa hiki.</p>
            @else
                <p>Vipendwa vyako vimehifadhiwa salama kwenye kifaa hiki. Ukiingia au kufungua akaunti, hadithi zitasawazishwa kiotomatiki.</p>
            @endauth
        </div>

        {{-- Upau wa Vichupo (Bookmarks Tabs) --}}
        <div class="bookmarks-tabs-bar" role="tablist" aria-label="Aina za Vipendwa">
            <button type="button" class="bookmark-tab active" data-tab="hadith" role="tab" aria-selected="true">
                <svg class="tab-svg" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                </svg>
                <span>Hadithi</span>
                <span class="bookmark-tab-badge" id="hadith-count-badge">0</span>
            </button>

            <button type="button" class="bookmark-tab" data-tab="quran" role="tab" aria-selected="false">
                <svg class="tab-svg" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                    <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                </svg>
                <span>Qur'ani</span>
                <span class="bookmark-tab-badge" id="quran-count-badge">0</span>
            </button>

            <button type="button" class="bookmark-tab" data-tab="duaa" role="tab" aria-selected="false">
                <svg class="tab-svg" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M12 2a10 10 0 0 1 10 10c0 5.5-4.5 10-10 10S2 17.5 2 12A10 10 0 0 1 12 2z"></path>
                    <path d="m9 12 2 2 4-4"></path>
                </svg>
                <span>Dua & Adhkar</span>
                <span class="bookmark-tab-badge" id="duaa-count-badge">0</span>
            </button>
        </div>

        {{-- Maudhui ya Vipendwa (Tab Content Container) --}}
        <div id="bookmarks-list" aria-live="polite">
            <div class="loading">
                <span class="spinner" aria-hidden="true"></span>
                <span>Inapakia vipendwa…</span>
            </div>
        </div>
    </div>

    <script>
    (() => {
        const container = document.getElementById('bookmarks-list');
        const isAuth = document.body?.dataset.auth === '1';
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        const tabs = document.querySelectorAll('.bookmark-tab');
        const esc = value => String(value ?? '').replace(/[&<>"']/g, char => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[char]));

        let currentTab = 'hadith';
        let cachedHadiths = null;

        // Helpers for LocalStorage
        const getHadithIds = () => {
            try { return JSON.parse(localStorage.getItem('hadith-bookmarks')) || []; } catch (_) { return []; }
        };
        const getQuranBookmarks = () => {
            try { return JSON.parse(localStorage.getItem('quran-bookmarks')) || []; } catch (_) { return []; }
        };
        const getDuaBookmarks = () => {
            try { return JSON.parse(localStorage.getItem('dua-bookmarks')) || []; } catch (_) { return []; }
        };

        const updateBadges = () => {
            const hBadge = document.getElementById('hadith-count-badge');
            const qBadge = document.getElementById('quran-count-badge');
            const dBadge = document.getElementById('duaa-count-badge');

            if (qBadge) qBadge.textContent = getQuranBookmarks().length;
            if (dBadge) dBadge.textContent = getDuaBookmarks().length;
            if (hBadge) {
                hBadge.textContent = cachedHadiths !== null ? cachedHadiths.length : getHadithIds().length;
            }
        };

        const showEmpty = (title, message, linkText, linkUrl) => {
            container.innerHTML = `
                <div class="empty-state">
                    <div class="empty-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" width="48" height="48" fill="none" stroke="var(--gold)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                        </svg>
                    </div>
                    <span class="section-label">VIPENDWA VYAKO</span>
                    <h3>${esc(title)}</h3>
                    <p>${esc(message)}</p>
                    <a class="primary-button" href="${linkUrl}">${esc(linkText)} →</a>
                </div>`;
        };

        // 1. Render Hadiths
        const renderHadiths = () => {
            const ids = getHadithIds();
            if (!isAuth && !ids.length) {
                showEmpty('Bado hujahifadhi hadith yoyote.', 'Fungua hadith yoyote na ubonyeze “Hifadhi kwenye Vipendwa” ili ionekane hapa.', 'Gundua Maktaba ya Hadithi', '{{ route('library') }}');
                return;
            }

            if (cachedHadiths !== null) {
                if (!cachedHadiths.length) {
                    showEmpty('Bado hujahifadhi hadith yoyote.', 'Fungua hadith yoyote na ubonyeze “Hifadhi kwenye Vipendwa” ili ionekane hapa.', 'Gundua Maktaba ya Hadithi', '{{ route('library') }}');
                    return;
                }
                displayHadiths(cachedHadiths);
                return;
            }

            container.innerHTML = `<div class="loading"><span class="spinner" aria-hidden="true"></span><span>Inapakia hadith ulizohifadhi…</span></div>`;

            const queryUrl = '{{ route('bookmarks.data') }}' + (ids.length ? '?ids=' + encodeURIComponent(ids.join(',')) : '');

            fetch(queryUrl)
                .then(r => { if (!r.ok) throw new Error('Failed to load'); return r.json(); })
                .then(items => {
                    cachedHadiths = items;
                    updateBadges();
                    if (!items.length) {
                        showEmpty(isAuth ? 'Bado hujahifadhi hadith yoyote kwenye akaunti yako.' : 'Hadith ulizohifadhi hazipatikani tena.', 'Fungua hadith yoyote na ubonyeze “Hifadhi kwenye Vipendwa” ili ionekane hapa.', 'Gundua Maktaba ya Hadithi', '{{ route('library') }}');
                        return;
                    }
                    if (isAuth) {
                        try { localStorage.setItem('hadith-bookmarks', JSON.stringify(items.map(i => String(i.id)))); } catch (_) {}
                    }
                    displayHadiths(items);
                })
                .catch(() => {
                    showEmpty('Hitilafu ya kupakia.', 'Imeshindwa kupakia hadith zako kwa sasa. Tafadhali jaribu tena.', 'Rudi Maktaba', '{{ route('library') }}');
                });
        };

        const displayHadiths = items => {
            container.innerHTML = `<div class="bookmarks-grid bookmarks-grid--hadith">` + items.map(item => `
                <article class="hadith-card" data-id="${item.id}">
                    <div class="card-top">
                        <span class="badge badge-${esc(item.collection_slug || 'bukhari')}">${esc(item.collection)}</span>
                        <span class="hadith-num">Na. ${esc(item.number)}</span>
                    </div>
                    <h3>${esc(item.chapter)}</h3>
                    <p class="translation excerpt">${esc(item.excerpt)}</p>
                    <div class="card-bottom">
                        <span class="card-location">${esc(item.book)}</span>
                        <a href="${item.url}" class="read-btn">
                            <span>Soma / Sikiliza</span>
                            <span class="read-arrow" aria-hidden="true">↗</span>
                        </a>
                    </div>
                    <button type="button" class="text-button remove-bookmark remove-btn-modern remove-hadith-btn" data-id="${item.id}">
                        <span>Ondoa kwenye Vipendwa</span>
                        <span aria-hidden="true">×</span>
                    </button>
                </article>
            `).join('') + `</div>`;
        };

        // 2. Render Quran Ayahs
        const renderQuran = () => {
            const list = getQuranBookmarks();
            if (!list.length) {
                showEmpty('Bado hujahifadhi aya ya Qur\'ani.', 'Unaposoma Qur\'ani, gusa alama ya nyota au hifadhi kwenye aya yoyote ili uitunze hapa.', 'Fungua Qur\'ani Tukufu', '{{ route('quran.index') }}');
                return;
            }

            container.innerHTML = `<div class="bookmarks-grid bookmarks-grid--quran">` + list.map(item => `
                <article class="quran-ayah-card bookmark-card-quran" data-key="${esc(item.id)}">
                    <div class="ayah-card-top-bar">
                        <div class="ayah-number-badge">
                            <span class="ayah-num-val">Sura ${esc(item.surah)} · Aya ${esc(item.verse)}</span>
                        </div>
                        <span class="quran-header-pill pill-makki">${esc(item.surah_name || 'Qur\'an')}</span>
                    </div>
                    <div class="ayah-arabic-wrap font-arabic" dir="rtl">
                        <p class="ayah-arabic-text">${esc(item.ar)}</p>
                    </div>
                    <div class="ayah-translation-wrap">
                        <p class="ayah-swahili-text">${esc(item.sw)}</p>
                    </div>
                    <div class="card-bottom" style="margin-top: 14px; display:flex; justify-content:space-between; align-items:center;">
                        <a href="${esc(item.url)}" class="read-btn">
                            <span>Soma Sura Yote</span>
                            <span class="read-arrow" aria-hidden="true">↗</span>
                        </a>
                        <button type="button" class="text-button remove-bookmark remove-btn-modern remove-quran-btn" data-key="${esc(item.id)}">
                            <span>Ondoa</span>
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                </article>
            `).join('') + `</div>`;
        };

        // 3. Render Duas
        const renderDuas = () => {
            const list = getDuaBookmarks();
            if (!list.length) {
                showEmpty('Bado hujahifadhi dua yoyote.', 'Fungua sehemu ya Dua & Adhkar na ubonyeze “Hifadhi kwenye Vipendwa” kwenye dua unayotaka kuikariri mara kwa mara.', 'Fungua Dua & Adhkar', '{{ route('duaa.index') }}');
                return;
            }

            container.innerHTML = `<div class="bookmarks-grid bookmarks-grid--duaa">` + list.map(item => `
                <article class="hadith-card bookmark-card-duaa" data-id="${esc(item.id)}">
                    <div class="card-top">
                        <span class="dua-category-badge" style="background:var(--gold-subtle); color:var(--gold); border:1px solid var(--border-color); padding:4px 10px; border-radius:999px; font-size:12px; font-weight:600;">${esc(item.category || 'Hisn al-Muslim')}</span>
                    </div>
                    <h3 style="margin-top:12px;">${esc(item.title)}</h3>
                    <p class="translation excerpt">${esc(item.excerpt)}</p>
                    <div class="card-bottom">
                        <a href="${esc(item.url)}" class="read-btn">
                            <span>Soma Dua</span>
                            <span class="read-arrow" aria-hidden="true">↗</span>
                        </a>
                        <button type="button" class="text-button remove-bookmark remove-btn-modern remove-dua-btn" data-id="${esc(item.id)}">
                            <span>Ondoa</span>
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                </article>
            `).join('') + `</div>`;
        };

        const renderCurrentTab = () => {
            tabs.forEach(tab => {
                const isActive = tab.dataset.tab === currentTab;
                tab.classList.toggle('active', isActive);
                tab.setAttribute('aria-selected', isActive ? 'true' : 'false');
            });

            if (currentTab === 'hadith') renderHadiths();
            else if (currentTab === 'quran') renderQuran();
            else if (currentTab === 'duaa') renderDuas();
        };

        // Tab click listeners
        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                currentTab = tab.dataset.tab;
                renderCurrentTab();
            });
        });

        // Removal Listeners
        container.addEventListener('click', async event => {
            // Remove Hadith
            const hBtn = event.target.closest('.remove-hadith-btn');
            if (hBtn) {
                const hid = String(hBtn.dataset.id);
                let list = getHadithIds().filter(id => String(id) !== hid);
                try { localStorage.setItem('hadith-bookmarks', JSON.stringify(list)); } catch (_) {}
                if (cachedHadiths) cachedHadiths = cachedHadiths.filter(i => String(i.id) !== hid);

                if (isAuth && csrfToken) {
                    try {
                        await fetch(`/vipendwa/toggle/${hid}`, {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                        });
                    } catch (_) {}
                }
                updateBadges();
                hBtn.closest('.hadith-card')?.remove();
                if (!container.querySelector('.hadith-card')) renderHadiths();
                return;
            }

            // Remove Quran
            const qBtn = event.target.closest('.remove-quran-btn');
            if (qBtn) {
                const key = String(qBtn.dataset.key);
                let list = getQuranBookmarks().filter(i => String(i.id) !== key);
                try { localStorage.setItem('quran-bookmarks', JSON.stringify(list)); } catch (_) {}
                updateBadges();
                qBtn.closest('.quran-ayah-card')?.remove();
                if (!container.querySelector('.quran-ayah-card')) renderQuran();
                return;
            }

            // Remove Dua
            const dBtn = event.target.closest('.remove-dua-btn');
            if (dBtn) {
                const did = String(dBtn.dataset.id);
                let list = getDuaBookmarks().filter(i => String(i.id) !== did);
                try { localStorage.setItem('dua-bookmarks', JSON.stringify(list)); } catch (_) {}
                updateBadges();
                dBtn.closest('.hadith-card')?.remove();
                if (!container.querySelector('.hadith-card')) renderDuas();
                return;
            }
        });

        // Initial setup
        updateBadges();
        renderCurrentTab();
    })();
    </script>
</x-layouts.app>
