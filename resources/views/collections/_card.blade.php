<article class="hadith-card" data-hadith-scope>
    <div class="card-top">
        <span class="badge badge-{{ $hadith->chapter->book->collection->slug }}">{{ $hadith->chapter->book->collection->name }}</span>
        <span class="hadith-num"><span data-i18n="pg_lib_no">Na.</span> {{ $hadith->number }}</span>
    </div>
    <div class="hadith-texts">
        <div data-reading="ar" class="arabic-box">
            <p class="arabic excerpt" lang="ar" dir="rtl">{{ $hadith->arabic }}</p>
        </div>
        <p data-reading="sw" @unless($hadith->english) data-no-en @endunless lang="{{ $hadith->has_swahili ? 'sw' : 'en' }}" class="translation swahili-text excerpt">
            @unless($hadith->has_swahili)<span class="en-only-tag" data-i18n="pg_lib_en_only">English</span> @endunless{{ $hadith->display_text }}
        </p>
        @if ($hadith->english)
            <p data-reading="en" lang="en" class="translation english-text excerpt">{{ $hadith->english }}</p>
        @endif
    </div>
    <p class="card-source"><span data-i18n="card_source_prefix">Chanzo: </span>{{ $hadith->source_name }}</p>
    <div class="card-bottom">
        <span></span>
        <div class="card-actions">
            <a href="{{ route('hadith.show', $hadith) }}" class="read-btn">
                <span data-i18n="card_read_btn">Soma</span>
                <span class="read-arrow" aria-hidden="true">↗</span>
            </a>
        </div>
    </div>
</article>
