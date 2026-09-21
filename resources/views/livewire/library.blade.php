@php
    // Wraps case-insensitive matches of the current search term in <mark>, on
    // already-escaped text so injecting the result with {!! !!} stays safe.
    $highlight = function (?string $text, string $term) {
        $escaped = e($text ?? '');
        $needle = trim(mb_substr($term, 0, 200));
        if ($needle === '') {
            return $escaped;
        }
        $pattern = '/'.preg_quote(e($needle), '/').'/iu';
        return preg_replace($pattern, '<mark>$0</mark>', $escaped);
    };
@endphp
<div>
    <section class="hero">
        <div class="hero-copy">
            <span class="eyebrow"><span></span> SAHIH AL-BUKHARI & SAHIH MUSLIM</span>
            <h1>HAZINA YA <br><em>HADITHI .</em></h1>
            <p>
Karibu Bayt Al-Hadith — nyumbani kwa mafundisho yenye thamani ya kudumu ya Mtume Muhammad ﷺ.<br> Gundua Hadith sahihi, ongeza uelewa wako wa Sunnah, na yafanye mafundisho yake kuwa sehemu ya maisha yako ya kila siku.</p>
        {{-- <p>Urithi wa elimu, karibu nawe.<br>Soma hadith kwa Kiarabu, Kiswahili na Kiingereza, pamoja na marejeo yake.</p> --}}
            <a class="hero-button" href="#maktaba">Anza kusoma <span aria-hidden="true">↗</span></a>
            <a class="hero-button hero-button-secondary" href="{{ route('hadith.today') }}">Hadith ya leo <span aria-hidden="true">✦</span></a>
        </div>
        <span class="hero-caption">MAKTABA YA HADITH <span aria-hidden="true">✦</span> ELIMU YENYE REJEA</span>
    </section>
    <div class="library-summary" aria-label="Takwimu za maktaba">
        <span><strong>{{ $total }}</strong> hadith zilizowekwa</span>
        <span><strong>{{ $collections->count() }}</strong> makusanyo</span>
        <span><strong>{{ $bookCount }}</strong> vitabu vyenye hadith</span>
        <span class="summary-note">Kiarabu · Kiswahili · English</span>
    </div>
    <section id="maktaba" class="library-shell" aria-label="Maktaba ya hadith">
        <aside class="sidebar">
            <div class="section-label">MAKUSANYO</div>
            <button class="collection-button {{ $collection === '' ? 'active' : '' }}" wire:click="chooseCollection('')" aria-pressed="{{ $collection === '' ? 'true' : 'false' }}"><span aria-hidden="true">▤</span> Makusanyo yote <span class="arrow">↗</span></button>
            @foreach($collections as $item)
                <button wire:key="collection-{{ $item->id }}" class="collection-button {{ $collection === $item->slug ? 'active' : '' }}" wire:click="chooseCollection('{{ $item->slug }}')" aria-pressed="{{ $collection === $item->slug ? 'true' : 'false' }}"><span aria-hidden="true">▤</span><span>{{ $item->name }}<small lang="ar" dir="rtl">{{ $item->name_ar }}</small></span><span class="arrow">↗</span></button>
            @endforeach
            <div class="filter-group"><label for="book">KITABU</label><select id="book" wire:model.live="book"><option value="">Vitabu vyote</option>@foreach($books as $item)<option value="{{ $item->id }}">{{ $collection === '' ? $item->collection->name.' · ' : '' }}{{ $item->number }}. {{ $item->title_sw }}</option>@endforeach</select></div>
            <div class="filter-group"><label for="chapter">MLANGO</label><select id="chapter" wire:model.live="chapter" @disabled($book === '')><option value="">{{ $book === '' ? 'Chagua kitabu kwanza' : 'Milango yote' }}</option>@foreach($chapters as $item)<option value="{{ $item->id }}">{{ $item->number }}. {{ $item->title_sw }}</option>@endforeach</select></div>
            <div class="sidebar-note"><span aria-hidden="true">◈</span><h3>Soma pamoja na rejea</h3><p>Maandishi na tafsiri kutoka HadeethEnc. Rejea ya kitabu na mlango ipo katika kila hadith.</p></div>
        </aside>
        <div class="results">
            <div class="results-heading"><div><span class="section-label">GUNDUA MAKTABA</span><h2>{{ $collections->firstWhere('slug', $collection)?->name ?? 'Makusanyo yote' }}</h2></div><span class="language-tag">العربية · Kiswahili · English</span></div>
            <form class="search-form" wire:submit.prevent="$refresh" role="search"><label class="sr-only" for="search">Tafuta hadith kwa maneno au namba</label><span class="search-icon" aria-hidden="true">⌕</span><input id="search" type="search" wire:model.live.debounce.350ms="search" maxlength="200" placeholder="Tafuta maneno au namba ya hadith…" autocomplete="off"><button type="submit">Tafuta <span aria-hidden="true">→</span></button></form>
            <div class="result-meta" aria-live="polite"><span>{{ number_format($hadiths->total()) }} hadith{{ trim($search) !== '' ? ' · “'.mb_substr($search, 0, 200).'”' : '' }}</span>@if($search !== '' || $collection !== '' || $book !== '' || $chapter !== '')<button class="text-button" wire:click="clearFilters">Ondoa vichujio ×</button>@else<span>Maandishi na tafsiri</span>@endif</div>
            @if($total > 0)<p class="coverage-note">Hadith zilizochaguliwa kutoka makusanyo yaliyoorodheshwa hapo juu; makusanyo kamili bado yanaongezwa.</p>@endif
            <div class="loading" wire:loading role="status">Inatafuta…</div>
            <div wire:loading.class="is-loading">
                @forelse($hadiths as $hadith)
                    <article class="hadith-card" wire:key="hadith-{{ $hadith->id }}"><div class="card-top"><span class="badge">{{ $hadith->chapter->book->collection->name }}</span><span>Na. {{ $hadith->number }}</span></div><h3>{{ $hadith->chapter->title_sw }}</h3><p data-reading="ar" class="arabic excerpt" lang="ar" dir="rtl">{{ $hadith->arabic }}</p><p data-reading="sw" lang="sw" class="translation excerpt">{!! $highlight($hadith->swahili, $search) !!}</p><p data-reading="en" lang="en" class="translation excerpt">{!! $hadith->english ? $highlight($hadith->english, $search) : 'Tafsiri ya Kiingereza bado haipatikani.' !!}</p><p class="card-source">Chanzo: {{ $hadith->source_name }} · Mwonekano wa awali</p><div class="card-bottom"><span>Kitabu {{ $hadith->chapter->book->number }} · Mlango {{ $hadith->chapter->number }}</span><a href="{{ route('hadith.show', $hadith) }}">Soma / Sikiliza <span aria-hidden="true">↗</span></a></div></article>
                @empty
                    <div class="empty-state"><div class="empty-icon" aria-hidden="true">▤</div><span class="section-label">{{ $total === 0 ? 'MAKTABA INAANDALIWA' : 'JARIBU UTAFUTAJI MWINGINE' }}</span><h3>{{ $total === 0 ? 'Safari ya elimu inaanzia hapa.' : 'Hakuna hadith iliyopatikana.' }}</h3><p>{{ $total === 0 ? 'Hadith za Kiarabu na tafsiri za Kiswahili zitaonekana hapa baada ya kuingizwa na kuhakikiwa pamoja na marejeo yake.' : 'Jaribu maneno machache, namba nyingine, au ondoa vichujio vya kitabu na mlango.' }}</p>@if($total === 0)<span class="status-pill"><span></span> Inasubiri maudhui yaliyohakikiwa</span>@else<button class="primary-button" wire:click="clearFilters">Onyesha hadith zote →</button>@endif</div>
                @endforelse
            </div>
            @if($hadiths->hasPages())<nav class="pagination" aria-label="Kurasa za matokeo"><button wire:click="previousPage" @disabled($hadiths->onFirstPage())>← Iliyotangulia</button><span>Ukurasa {{ $hadiths->currentPage() }} / {{ $hadiths->lastPage() }}</span><button wire:click="nextPage" @disabled(!$hadiths->hasMorePages())>Inayofuata →</button></nav>@endif
        </div>
    </section>
</div>
