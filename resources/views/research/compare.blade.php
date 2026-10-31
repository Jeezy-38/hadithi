<x-layouts.app title="Linganisha Hadith · Utafiti">
    <div class="reader-shell lc-shell">
        <a class="back-link" href="{{ route('research.hub') }}">
            <span class="back-arrow" aria-hidden="true">←</span>
            <span data-i18n="pg_rs_title">Utafiti na Ramani ya Maarifa</span>
        </a>

        <div class="reader-heading">
            <span class="eyebrow"><span class="eyebrow-dot"></span> <b data-i18n="pg_rs_eyebrow">UTAFITI</b></span>
            <h1 data-i18n="pg_rs_compare_title">Linganisha Hadith</h1>
            <p data-i18n="pg_rs_similarity" data-i18n-vars='{"p":{{ $score }}}'>Ufanano wa maneno: {{ $score }}%</p>
        </div>

        <div class="lc-note rs-disclaimer"><span data-i18n="pg_rs_disclaimer">Ufanano huu unategemea maneno yanayoshirikiana tu. Si hukumu ya kielimu kuhusu maana au daraja la hadith.</span></div>

        @if(count($shared))
            <section class="lc-card">
                <h2 class="lc-card-label" data-i18n="pg_rs_shared">Maneno yanayoshirikiana</h2>
                <p class="rs-chips">@foreach($shared as $w)<span class="rs-chip">{{ $w }}</span>@endforeach</p>
            </section>
        @endif

        <div class="rs-compare">
            @foreach([$a, $b] as $h)
                <article class="lc-card">
                    <p class="lc-q-count">{{ $h->chapter->book->collection->name }} · Na. {{ $h->number }}</p>
                    <h2 class="lc-card-label">{{ \Illuminate\Support\Str::limit($h->chapter->title_sw, 70) }}</h2>
                    <p class="arabic arabic-text font-arabic lc-arabic" lang="ar" dir="rtl">{{ $h->arabic }}</p>
                    <p class="lc-text rs-sw">{{ $h->swahili }}</p>
                    @if($h->grade)<p class="lc-hint-text"><span data-i18n="pg_rs_grade">Daraja</span>: {{ $h->grade }}</p>@endif
                    <div class="lc-actions rs-actions">
                        <a class="lc-btn lc-btn-small" href="{{ route('research.graph', $h) }}" data-i18n="pg_rs_map">Ramani</a>
                        <a class="lc-btn lc-btn-small lc-btn-ghost" href="{{ route('hadith.show', $h) }}" data-i18n="pg_rs_open">Soma</a>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</x-layouts.app>
