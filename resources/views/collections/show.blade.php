@php
    $desc = $collection->name.': vitabu '.$books->count().' na hadith '.number_format($totalHadiths).' zilizopangwa kwa vitabu na mada. Chagua kitabu uone mada zake, kisha usome hadith kwa Kiarabu na tafsiri.';
@endphp
<x-layouts.app :title="$collection->name.' - Vitabu na Mada'" :description="$desc" :canonical="route('collections.show', $collection->slug)">
    <link rel="stylesheet" href="{{ asset('css/collections.css') }}?v={{ filemtime(public_path('css/collections.css')) }}">
    <div class="coll-page">
        <nav class="coll-crumbs" aria-label="Breadcrumb">
            <a href="{{ route('library') }}#makusanyo" data-i18n="pg_cl_crumb">Makusanyo</a>
            <span aria-hidden="true">›</span>
            <span class="coll-crumb-current">{{ $collection->name }}</span>
        </nav>

        <header class="coll-head">
            @if (! empty($meta['cover_image']))
                <img class="coll-head-cover" src="{{ asset($meta['cover_image']) }}" alt="" width="70" height="108">
            @endif
            <div class="coll-head-body">
                <h1 class="coll-title">{{ $collection->name }}</h1>
                @if (preg_match('/\p{Arabic}/u', (string) $collection->name_ar))<p class="coll-title-ar" lang="ar" dir="rtl">{{ $collection->name_ar }}</p>@endif
                @if (! empty($meta['author']))
                    <p class="coll-author">{{ $meta['author'] }}@if (! empty($meta['desc'])) · {{ $meta['desc'] }}@endif</p>
                @endif
            </div>
            <div class="coll-stats">
                <div><strong>{{ number_format($books->count()) }}</strong><span data-i18n="pg_cl_books">Vitabu</span></div>
                <div><strong>{{ number_format($totalHadiths) }}</strong><span data-i18n="pg_cl_hadiths">Hadith</span></div>
            </div>
        </header>

        <p class="coll-hint" data-i18n="pg_cl_pick_book">Chagua kitabu ili kuona mada zake.</p>

        <div class="coll-grid">
            @forelse ($books as $book)
                @php($st = $stats[$book->id])
                <a class="coll-card" href="{{ route('collections.book', [$collection->slug, $book->id]) }}">
                    <span class="coll-num" aria-hidden="true">{{ $book->number }}</span>
                    <span class="coll-card-body">
                        <span class="coll-card-title">@include('collections._title')</span>
                        <span class="coll-card-meta">
                            <span data-i18n="pg_cl_topic_n" data-i18n-vars='{"n":"{{ number_format($st->chapters) }}"}'>{{ number_format($st->chapters) }} mada</span>
                            · <span data-i18n="pg_cl_hadith_n" data-i18n-vars='{"n":"{{ number_format($st->hadiths) }}"}'>{{ number_format($st->hadiths) }} hadith</span>
                        </span>
                    </span>
                    <span class="coll-arrow" aria-hidden="true">→</span>
                </a>
            @empty
                <p class="coll-empty" data-i18n="pg_cl_empty">Hakuna hadith bado kwenye mkusanyo huu.</p>
            @endforelse
        </div>
    </div>
</x-layouts.app>
