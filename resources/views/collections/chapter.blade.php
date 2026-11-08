@php
    $bookTitle = $book->titleFor('sw');
    $page = $hadiths->currentPage();
    $desc = $collection->name.' - '.$bookTitle.' - '.$chapter->title_sw.': soma hadith kwa Kiarabu na tafsiri.';
@endphp
<x-layouts.app :title="$collection->name.' - '.\Illuminate\Support\Str::limit($chapter->title_sw, 70)" :description="$desc" :canonical="route('collections.chapter', [$collection->slug, $book->id, $chapter->id])" :noindex="$page > 1">
    <link rel="stylesheet" href="{{ asset('css/collections.css') }}?v={{ filemtime(public_path('css/collections.css')) }}">
    <div class="coll-page" data-hadith-scope>
        <nav class="coll-crumbs" aria-label="Breadcrumb">
            <a href="{{ route('library') }}#makusanyo" data-i18n="pg_cl_crumb">Makusanyo</a>
            <span aria-hidden="true">›</span>
            <a href="{{ route('collections.show', $collection->slug) }}">{{ $collection->name }}</a>
            <span aria-hidden="true">›</span>
            <a href="{{ route('collections.book', [$collection->slug, $book->id]) }}">@include('collections._title')</a>
            <span aria-hidden="true">›</span>
            <span class="coll-crumb-current">{{ \Illuminate\Support\Str::limit($chapter->title_sw, 60) }}</span>
        </nav>

        <header class="coll-head">
            <span class="coll-num coll-num-lg" aria-hidden="true">{{ $chapter->number }}</span>
            <div class="coll-head-body">
                <p class="coll-kicker" data-i18n="pg_cl_topic">Mada</p>
                <h1 class="coll-title coll-title-sm">{{ $chapter->title_sw }}</h1>
                @if ($chapter->title_ar)<p class="coll-title-ar" lang="ar" dir="rtl">{{ $chapter->title_ar }}</p>@endif
            </div>
        </header>

        <div class="coll-hadiths">
            @foreach ($hadiths as $hadith)
                @include('collections._card')
            @endforeach
        </div>

        @if ($hadiths->hasPages())
            <div class="coll-pager">
                @if ($hadiths->previousPageUrl())
                    <a class="coll-pager-btn" href="{{ $hadiths->previousPageUrl() }}" rel="prev">← <span data-i18n="pg_cl_prev">Iliyotangulia</span></a>
                @else<span></span>@endif
                <span class="coll-pager-page">{{ $page }}</span>
                @if ($hadiths->hasMorePages())
                    <a class="coll-pager-btn" href="{{ $hadiths->nextPageUrl() }}" rel="next"><span data-i18n="pg_cl_next">Inayofuata</span> →</a>
                @else<span></span>@endif
            </div>
        @endif

        <a class="coll-back" href="{{ route('collections.book', [$collection->slug, $book->id]) }}">← <span data-i18n="pg_cl_back_topics">Rudi kwenye mada za kitabu</span></a>
    </div>
</x-layouts.app>
