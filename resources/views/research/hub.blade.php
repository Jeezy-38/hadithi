<x-layouts.app title="Utafiti na Ramani ya Maarifa">
    <div class="reader-shell lc-shell">
        <a class="back-link" href="{{ route('library') }}">
            <span class="back-arrow" aria-hidden="true">←</span>
            <span data-i18n="pg_lc_back">Nyuma</span>
        </a>

        <div class="reader-heading">
            <span class="eyebrow"><span class="eyebrow-dot"></span> <b data-i18n="pg_rs_eyebrow">UTAFITI</b></span>
            <h1 data-i18n="pg_rs_title">Utafiti na Ramani ya Maarifa</h1>
            <p data-i18n="pg_rs_sub">Tafuta hadith, ona ramani ya uhusiano wake na mada na hadith nyingine, au zilinganishe mbili upande kwa upande.</p>
        </div>

        <form class="search-form book-search-form library-search rs-search" method="get" action="{{ route('research.hub') }}" role="search">
            @if($picked)<input type="hidden" name="a" value="{{ $picked->id }}">@endif
            <label class="sr-only" for="rs-q" data-i18n="pg_lib_search_label">Tafuta hadith kwa maneno au namba</label>
            <span class="search-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
            </span>
            <input id="rs-q" type="search" name="q" value="{{ $q }}" maxlength="100" placeholder="Tafuta kwa neno au namba…" data-i18n-placeholder="pg_rs_placeholder">
            <button type="submit"><span data-i18n="search_btn">Tafuta</span> <span aria-hidden="true">→</span></button>
        </form>

        @if($picked)
            <div class="lc-note">
                <span data-i18n="pg_rs_picked" data-i18n-vars='{"n":"{{ $picked->number }}"}'>Umechagua Na. {{ $picked->number }}. Tafuta hadith ya pili ili kulinganisha.</span>
                <a class="lc-btn lc-btn-small lc-btn-ghost" href="{{ route('research.hub', ['q' => $q]) }}" data-i18n="pg_rs_clear">Ondoa</a>
            </div>
        @endif

        @if($q !== '')
            <h2 class="lc-section-title">{{ $results->count() }} <span data-i18n="pg_rs_results">matokeo</span></h2>
            <div class="rs-results">
                @forelse($results as $h)
                    <article class="lc-card rs-result">
                        <p class="lc-q-count">{{ $h->chapter->book->collection->name }} · Na. {{ $h->number }}</p>
                        <p class="lc-text">{{ \Illuminate\Support\Str::limit($h->title ?: $h->swahili, 170) }}</p>
                        <div class="lc-actions rs-actions">
                            <a class="lc-btn lc-btn-small" href="{{ route('research.graph', $h) }}" data-i18n="pg_rs_map">Ramani</a>
                            @if($picked && $picked->id !== $h->id)
                                <a class="lc-btn lc-btn-small lc-btn-ghost" href="{{ route('research.compare', ['a' => $picked->id, 'b' => $h->id]) }}" data-i18n="pg_rs_compare_now">Linganisha na hii</a>
                            @else
                                <a class="lc-btn lc-btn-small lc-btn-ghost" href="{{ route('research.hub', ['q' => $q, 'a' => $h->id]) }}" data-i18n="pg_rs_pick">Chagua kulinganisha</a>
                            @endif
                            <a class="lc-btn lc-btn-small lc-btn-ghost" href="{{ route('hadith.show', $h) }}" data-i18n="pg_rs_open">Soma</a>
                        </div>
                    </article>
                @empty
                    <p class="lc-note" data-i18n="pg_rs_none">Hakuna hadith iliyopatikana.</p>
                @endforelse
            </div>
        @endif

        <section id="ramani" class="lc-card rs-about">
            <h2 class="lc-card-label" data-i18n="pg_rs_about_title">Ramani ya maarifa ni nini?</h2>
            <p class="lc-text" data-i18n="pg_rs_about">Ukitafuta hadith na kubonyeza "Ramani", utaona mchoro unaounganisha hadith hiyo na kitabu chake, mada zinazohusiana, na hadith zenye maneno yanayofanana. Bonyeza nodi yoyote kuendelea kuvinjari.</p>
        </section>

        <section class="lc-card rs-about">
            <h2 class="lc-card-label" data-i18n="pg_rs_isnad_title">Isnad na wasifu wa wapokezi</h2>
            <p class="lc-text" data-i18n="pg_rs_isnad">Data ya wapokezi (isnad) bado haijaongezwa kwenye Bayt Ul Hadith. Itaonekana hapa pindi itakapopatikana kutoka chanzo kinachoaminika, kwa marejeo kamili.</p>
        </section>
    </div>
</x-layouts.app>
