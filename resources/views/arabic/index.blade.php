<x-layouts.app title="Arabic Analyzer — Chunguza Maneno">
    <div class="reader-shell lc-shell">
        <a class="back-link" href="{{ route('library') }}">
            <span class="back-arrow" aria-hidden="true">←</span>
            <span data-i18n="pg_lc_back">Nyuma</span>
        </a>

        <div class="reader-heading">
            <span class="eyebrow"><span class="eyebrow-dot"></span> <b data-i18n="pg_ar_eyebrow">ARABIC ANALYZER</b></span>
            <h1 data-i18n="pg_ar_title">Chunguza Maneno ya Kiarabu</h1>
            <p data-i18n="pg_ar_sub">Chagua hadith, kisha bonyeza neno lolote la Kiarabu uone hadith nyingine zinazolitumia.</p>
        </div>

        <form class="search-form book-search-form library-search rs-search" method="get" action="{{ route('arabic.index') }}" role="search">
            <label class="sr-only" for="ar-q" data-i18n="pg_lib_search_label">Tafuta hadith kwa maneno au namba</label>
            <span class="search-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
            </span>
            <input id="ar-q" type="search" name="q" value="{{ $q }}" maxlength="100" placeholder="Tafuta kwa neno au namba…" data-i18n-placeholder="pg_rs_placeholder">
            <button type="submit"><span data-i18n="search_btn">Tafuta</span> <span aria-hidden="true">→</span></button>
        </form>

        @if($hadith)
            <div class="ar-card" id="ar-app" data-endpoint="{{ route('arabic.word') }}">
                <div class="ar-text" dir="rtl" lang="ar">
                    @foreach($words as $w)<button type="button" class="ar-word" data-w="{{ $w }}">{{ $w }}</button> @endforeach
                </div>
                <p class="ar-meta"><a href="{{ route('hadith.show', $hadith) }}">Na. {{ $hadith->number }} · {{ $hadith->title }}</a></p>
                <div class="ar-panel" id="ar-panel" aria-live="polite">
                    <p class="ar-hint" data-i18n="pg_ar_hint">Bonyeza neno ili kuona matumizi yake.</p>
                </div>
                <p class="ar-note" data-i18n="pg_ar_note">Toleo la awali: utafutaji wa neno kwa maandishi. Mzizi na sarufi kamili vitakuja baadaye.</p>
            </div>
            <script src="{{ asset('js/arabic.js') }}?v=1" defer></script>
        @else
            <div class="rs-results">
                @forelse($results as $h)
                    <article class="lc-card rs-result">
                        <p class="lc-q-count">{{ $h->chapter->book->collection->name }} · Na. {{ $h->number }}</p>
                        <p class="lc-text" dir="rtl" lang="ar">{{ \Illuminate\Support\Str::limit($h->arabic, 110) }}</p>
                        <div class="lc-actions rs-actions">
                            <a class="lc-btn lc-btn-small" href="{{ route('arabic.index', $h) }}" data-i18n="pg_ar_open">Chunguza</a>
                        </div>
                    </article>
                @empty
                    @if($q !== '')<p class="lc-note" data-i18n="pg_rs_none">Hakuna hadith iliyopatikana.</p>@endif
                @endforelse
            </div>
        @endif
    </div>
</x-layouts.app>
