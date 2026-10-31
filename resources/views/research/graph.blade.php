<x-layouts.app :title="'Ramani ya Maarifa · Na. ' . $hadith->number">
    <div class="reader-shell lc-shell">
        <a class="back-link" href="{{ route('hadith.show', $hadith) }}">
            <span class="back-arrow" aria-hidden="true">←</span>
            <span data-i18n="pg_rs_back_hadith">Rudi kwenye hadith</span>
        </a>

        <div class="reader-heading">
            <span class="eyebrow"><span class="eyebrow-dot"></span> <b data-i18n="pg_rs_map_eyebrow">RAMANI YA MAARIFA</b></span>
            <h1>{{ $hadith->chapter->book->collection->name }} · Na. {{ $hadith->number }}</h1>
            <p>{{ \Illuminate\Support\Str::limit($hadith->title ?: $hadith->swahili, 160) }}</p>
        </div>

        <div class="kg-wrap">
            <svg class="kg-svg" viewBox="0 0 360 360" role="img" aria-label="Ramani ya maarifa ya hadith">
                @foreach($ring as $node)
                    <line class="kg-edge" x1="180" y1="180" x2="{{ $node['x'] }}" y2="{{ $node['y'] }}"/>
                @endforeach
                @foreach($ring as $node)
                    <a href="{{ $node['href'] }}" class="kg-node kg-{{ $node['type'] }}">
                        <title>{{ $node['full'] }}</title>
                        <circle cx="{{ $node['x'] }}" cy="{{ $node['y'] }}" r="20"/>
                        <text x="{{ $node['x'] }}" y="{{ $node['y'] + 34 }}" text-anchor="middle">{{ $node['label'] }}</text>
                    </a>
                @endforeach
                <g class="kg-node kg-center">
                    <circle cx="180" cy="180" r="30"/>
                    <text x="180" y="184" text-anchor="middle">Na. {{ $hadith->number }}</text>
                </g>
            </svg>
        </div>

        <div class="rs-legend">
            <span class="rs-dot kg-dot-book"></span><span data-i18n="pg_rs_lg_book">Kitabu</span>
            <span class="rs-dot kg-dot-chapter"></span><span data-i18n="pg_rs_lg_chapter">Mlango</span>
            <span class="rs-dot kg-dot-topic"></span><span data-i18n="pg_rs_lg_topic">Mada</span>
            <span class="rs-dot kg-dot-similar"></span><span data-i18n="pg_rs_lg_similar">Hadith inayofanana</span>
        </div>

        @if($topics->isNotEmpty())
            <section class="lc-card">
                <h2 class="lc-card-label" data-i18n="pg_rs_topics">Mada zinazohusiana</h2>
                <p class="rs-chips">@foreach($topics as $t)<a class="rs-chip" href="{{ route('topics.show', $t['slug']) }}">{{ $t['title_sw'] }}</a>@endforeach</p>
            </section>
        @endif

        <section class="lc-card">
            <h2 class="lc-card-label" data-i18n="pg_rs_similar_title">Hadith zinazofanana</h2>
            @forelse($similar as $s)
                <div class="rs-sim">
                    <a class="lc-link" href="{{ route('research.graph', $s['hadith']) }}">{{ $s['hadith']->chapter->book->collection->name }} · Na. {{ $s['hadith']->number }}</a>
                    <span class="rs-score">{{ round($s['score'] * 100) }}%</span>
                    <p class="lc-text">{{ \Illuminate\Support\Str::limit($s['hadith']->swahili, 140) }}</p>
                    <p class="rs-chips">@foreach($s['shared'] as $w)<span class="rs-chip">{{ $w }}</span>@endforeach</p>
                    <a class="lc-btn lc-btn-small lc-btn-ghost" href="{{ route('research.compare', ['a' => $hadith->id, 'b' => $s['hadith']->id]) }}" data-i18n="pg_rs_compare_now">Linganisha na hii</a>
                </div>
            @empty
                <p class="lc-hint-text" data-i18n="pg_rs_no_similar">Hakuna hadith yenye maneno yanayofanana vya kutosha.</p>
            @endforelse
            <p class="lc-hint-text" data-i18n="pg_rs_disclaimer">Ufanano huu unategemea maneno yanayoshirikiana tu. Si hukumu ya kielimu kuhusu maana au daraja la hadith.</p>
        </section>

        <section class="lc-card">
            <h2 class="lc-card-label" data-i18n="pg_rs_isnad_title">Isnad na wasifu wa wapokezi</h2>
            <p class="lc-text" data-i18n="pg_rs_isnad">Data ya wapokezi (isnad) bado haijaongezwa kwenye Bayt Ul Hadith. Itaonekana hapa pindi itakapopatikana kutoka chanzo kinachoaminika, kwa marejeo kamili.</p>
        </section>
    </div>
</x-layouts.app>
