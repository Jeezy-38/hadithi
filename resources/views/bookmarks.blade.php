<x-layouts.app title="Vipendwa vyangu · Hadith">
    <div class="reader-shell">
        <a class="back-link" href="{{ route('library') }}">← Rudi kwenye maktaba</a>
        <div class="reader-heading">
            <span class="eyebrow">VIPENDWA</span>
            <h1>Hadith ulizozihifadhi</h1>
            <p>Zinahifadhiwa kwenye kifaa/kivinjari chako pekee; hazitumwi wala kuonekana na mtu mwingine.</p>
        </div>
        <div id="bookmarks-list" aria-live="polite">
            <p class="loading">Inapakia…</p>
        </div>
    </div>
    <script>
    (() => {
        const container = document.getElementById('bookmarks-list');
        const esc = value => String(value).replace(/[&<>"']/g, char => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[char]));
        let ids = [];
        try { ids = JSON.parse(localStorage.getItem('hadith-bookmarks')) || []; } catch (_) {}

        const showEmpty = message => {
            container.innerHTML = `<div class="empty-state"><div class="empty-icon" aria-hidden="true">☆</div><h3>${esc(message)}</h3><p>Fungua hadith yoyote na bonyeza “Hifadhi kwenye Vipendwa” ili ionekane hapa.</p></div>`;
        };

        if (!ids.length) { showEmpty('Bado hujahifadhi hadith yoyote.'); return; }

        fetch('{{ route('bookmarks.data') }}?ids=' + encodeURIComponent(ids.join(',')))
            .then(response => { if (!response.ok) throw new Error('bad response'); return response.json(); })
            .then(items => {
                if (!items.length) { showEmpty('Hadith ulizohifadhi hazipatikani tena kwenye maktaba.'); return; }
                container.innerHTML = items.map(item => `
                    <article class="hadith-card" data-id="${item.id}">
                        <div class="card-top"><span class="badge">${esc(item.collection)}</span><span>Na. ${esc(item.number)}</span></div>
                        <h3>${esc(item.chapter)}</h3>
                        <p class="translation excerpt">${esc(item.excerpt)}</p>
                        <div class="card-bottom">
                            <span>${esc(item.book)}</span>
                            <a href="${item.url}">Soma / Sikiliza <span aria-hidden="true">↗</span></a>
                        </div>
                        <button type="button" class="text-button remove-bookmark" data-id="${item.id}">Ondoa kwenye Vipendwa ×</button>
                    </article>
                `).join('');
            })
            .catch(() => showEmpty('Imeshindwa kupakia Vipendwa. Jaribu tena baadaye.'));

        container.addEventListener('click', event => {
            const button = event.target.closest('.remove-bookmark');
            if (!button) return;
            let list = [];
            try { list = JSON.parse(localStorage.getItem('hadith-bookmarks')) || []; } catch (_) {}
            list = list.filter(id => id !== button.dataset.id);
            try { localStorage.setItem('hadith-bookmarks', JSON.stringify(list)); } catch (_) {}
            button.closest('.hadith-card')?.remove();
            if (!container.querySelector('.hadith-card')) showEmpty('Umeondoa hadith zote kwenye Vipendwa.');
        });
    })();
    </script>
</x-layouts.app>
