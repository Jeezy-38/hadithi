@php
    $bookTitle = $book->titleFor('sw');
    $desc = $collection->name.' - '.$bookTitle.': mada '.$chapters->count().' na hadith '.number_format($totalHadiths).'. Chagua mada uone hadith zake kwa Kiarabu na tafsiri.';
@endphp
<x-layouts.app :title="$collection->name.' - '.$bookTitle" :description="$desc" :canonical="route('collections.book', [$collection->slug, $book->id])">
    <link rel="stylesheet" href="{{ asset('css/collections.css') }}?v={{ filemtime(public_path('css/collections.css')) }}">
    <div class="coll-page">
        <nav class="coll-crumbs" aria-label="Breadcrumb">
            <a href="{{ route('library') }}#makusanyo" data-i18n="pg_cl_crumb">Makusanyo</a>
            <span aria-hidden="true">›</span>
            <a href="{{ route('collections.show', $collection->slug) }}">{{ $collection->name }}</a>
            <span aria-hidden="true">›</span>
            <span class="coll-crumb-current">@include('collections._title')</span>
        </nav>

        <header class="coll-head">
            <span class="coll-num coll-num-lg" aria-hidden="true">{{ $book->number }}</span>
            <div class="coll-head-body">
                <p class="coll-kicker" data-i18n="pg_cl_book">Kitabu</p>
                <h1 class="coll-title">@include('collections._title')</h1>
                @if ($book->title_ar)<p class="coll-title-ar" lang="ar" dir="rtl">{{ $book->title_ar }}</p>@endif
            </div>
            <div class="coll-stats">
                <div><strong>{{ number_format($chapters->count()) }}</strong><span data-i18n="pg_cl_topics">Mada</span></div>
                <div><strong>{{ number_format($totalHadiths) }}</strong><span data-i18n="pg_cl_hadiths">Hadith</span></div>
            </div>
        </header>

        <p class="coll-hint" data-i18n="pg_cl_pick_topic">Chagua mada ili kusoma hadith zake.</p>

        <div class="coll-list">
            @foreach ($chapters as $chapter)
                <a class="coll-row" href="{{ route('collections.chapter', [$collection->slug, $book->id, $chapter->id]) }}">
                    <span class="coll-num" aria-hidden="true">{{ $chapter->number }}</span>
                    <span class="coll-row-title">{{ $chapter->title_sw }}</span>
                    <span class="coll-count"><span data-i18n="pg_cl_hadith_n" data-i18n-vars='{"n":"{{ number_format($stats[$chapter->id]->hadiths) }}"}'>{{ number_format($stats[$chapter->id]->hadiths) }} hadith</span></span>
                    <span class="coll-arrow" aria-hidden="true">→</span>
                </a>
            @endforeach
        </div>
    </div>
</x-layouts.app>
