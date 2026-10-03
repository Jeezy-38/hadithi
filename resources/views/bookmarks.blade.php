<x-layouts.app title="Vipendwa vyangu · Hadith">
    <div class="reader-shell">
        <a class="back-link" href="{{ route('library') }}">
            <span class="back-arrow" aria-hidden="true">←</span>
            <span>Rudi kwenye maktaba</span>
        </a>
        <div class="reader-heading">
            <span class="eyebrow"><span class="eyebrow-dot"></span> VIPENDWA</span>
            <h1>Hadith ulizozihifadhi</h1>
            @auth
                <p>Zinasawazishwa kwenye akaunti yako ili uweze kuzisoma kwenye kifaa chochote ulichoingia.</p>
            @else
                <p>Zinahifadhiwa kwenye kifaa hiki. Ukiingia au kufungua akaunti, zitasawazishwa moja kwa moja kwenye akaunti yako.</p>
            @endauth
        </div>
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
        const esc = value => String(value).replace(/[&<>"']/g, char => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[char]));
        let ids = [];
        try { ids = JSON.parse(localStorage.getItem('hadith-bookmarks')) || []; } catch (_) {}

        const showEmpty = message => {
            container.innerHTML = `
                <div class="empty-state">
                    <div class="empty-icon" aria-hidden="true">☆</div>
                    <span class="section-label">VIPENDWA VYAKO</span>
                    <h3>${esc(message)}</h3>
                    <p>Fungua hadith yoyote na bonyeza “Hifadhi kwenye Vipendwa” ili ionekane hapa kwa ajili ya kusoma tena wakati wowote.</p>
                    <a class="primary-button" href="{{ route('library') }}">Gundua maktaba sasa →</a>
                </div>`;
        };

        if (!isAuth && !ids.length) {
            showEmpty('Bado hujahifadhi hadith yoyote.');
            return;
        }

        const queryUrl = '{{ route('bookmarks.data') }}' + (ids.length ? '?ids=' + encodeURIComponent(ids.join(',')) : '');

        fetch(queryUrl)
            .then(response => { if (!response.ok) throw new Error('bad response'); return response.json(); })
            .then(items => {
                if (!items.length) {
                    showEmpty(isAuth ? 'Bado hujahifadhi hadith yoyote kwenye akaunti yako.' : 'Hadith ulizohifadhi hazipatikani tena.');
                    return;
                }
                // Once synced to account, update localStorage cache with all active IDs
                if (isAuth) {
                    try { localStorage.setItem('hadith-bookmarks', JSON.stringify(items.map(i => String(i.id)))); } catch (_) {}
                }
                container.innerHTML = items.map(item => `
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
                        <button type="button" class="text-button remove-bookmark remove-btn-modern" data-id="${item.id}">
                            <span>Ondoa kwenye Vipendwa</span>
                            <span aria-hidden="true">×</span>
                        </button>
                    </article>
                `).join('');
            })
            .catch(() => showEmpty('Imeshindwa kupakia Vipendwa. Jaribu tena baadaye.'));

        container.addEventListener('click', async event => {
            const button = event.target.closest('.remove-bookmark');
            if (!button) return;
            const hadithId = String(button.dataset.id);
            let list = [];
            try { list = JSON.parse(localStorage.getItem('hadith-bookmarks')) || []; } catch (_) {}
            list = list.filter(id => String(id) !== hadithId);
            try { localStorage.setItem('hadith-bookmarks', JSON.stringify(list)); } catch (_) {}

            if (isAuth && csrfToken) {
                try {
                    await fetch(`/vipendwa/toggle/${hadithId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                        },
                    });
                } catch (_) {}
            }

            button.closest('.hadith-card')?.remove();
            if (!container.querySelector('.hadith-card')) showEmpty('Umeondoa hadith zote kwenye Vipendwa.');
        });
    })();
    </script>
</x-layouts.app>
