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
    @if($collection === '' && $search === '')
        <section class="hero library-hero">
            <div class="hero-glow" aria-hidden="true"></div>
            <div class="hero-copy">
                <span class="eyebrow" data-i18n="hero_eyebrow"><span class="eyebrow-dot"></span> BUKHARI · MUSLIM · TIRMIDHI · ABU DAWUD · AHMAD</span>
                <h1 data-i18n="hero_h1">BAYT AL<br><em>HADITH.</em></h1>
                <p data-i18n="hero_desc">
    Karibu Bayt Al-Hadith — nyumbani kwa mafundisho yenye thamani ya kudumu ya Mtume Muhammad ﷺ.<br> Gundua Hadith sahihi, ongeza uelewa wako wa Sunnah, na yafanye mafundisho yake kuwa sehemu ya maisha yako ya kila siku.</p>
                <div class="hero-actions">
                    <a class="hero-button" href="#maktaba">
                        <span data-i18n="hero_btn_start">Anza kusoma</span>
                        <svg viewBox="0 0 20 20" width="16" height="16" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M5.22 14.78a.75.75 0 001.06 0l7.22-7.22v5.69a.75.75 0 001.5 0v-7.5a.75.75 0 00-.75-.75h-7.5a.75.75 0 000 1.5h5.69l-7.22 7.22a.75.75 0 000 1.06z" clip-rule="evenodd" />
                        </svg>
                    </a>
                </div>
            </div>
            <span class="hero-caption" data-i18n="hero_caption"><span aria-hidden="true">✦</span></span>
        </section>
    @endif

    <section id="maktaba" class="library-shell" aria-label="Maktaba ya hadith">
        <div class="results">
            <div class="loading" wire:loading role="status">
                <span class="spinner" aria-hidden="true"></span>
                <span data-i18n="searching_status">Inatafuta…</span>
            </div>

            <div wire:loading.class="is-loading">
                @if($collection === '' && $search === '' && $hadiths->currentPage() === 1)
                    {{-- Hadith Books Catalog View --}}
                    <section class="hadith-books-catalog" aria-label="Vitabu vya Hadithi">
                        <div class="hadith-books-header">
                            <div class="hadith-books-title-group">
                                <div class="hadith-header-art" aria-hidden="true">
                                    <svg viewBox="0 0 36 36" width="34" height="34" fill="none">
                                        <!-- Leather spine and book cover -->
                                        <rect x="7" y="4" width="23" height="28" rx="3.5" fill="#5c3818" stroke="#D4AF37" stroke-width="1.4"/>
                                        <path d="M11 4V32" stroke="#854d1f" stroke-width="2"/>
                                        <path d="M12 4V32" stroke="#D4AF37" stroke-width="0.8"/>
                                        <!-- Bookmark ribbon -->
                                        <path d="M17 4V16L20 13.5L23 16V4" fill="#D4AF37"/>
                                        <!-- Golden Medallion ornament -->
                                        <circle cx="21" cy="22" r="4.5" fill="#3d220a" stroke="#E5C158" stroke-width="1"/>
                                        <polygon points="21,18.5 22.2,21 24.8,21.4 22.8,23 23.5,25.6 21,24 18.5,25.6 19.2,23 17.2,21.4 19.8,21" fill="#E5C158"/>
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="hadith-books-title">Hadith Books</h2>
                                </div>
                            </div>
                            @if($total === 0)
                                <div class="catalog-empty-status">
                                    <span class="status-pill"><span></span> Inasubiri maudhui yaliyohakikiwa</span>
                                </div>
                            @endif
                        </div>

                        <div class="hadith-books-list">
                            @foreach($collections as $item)
                                @php
                                    $meta = $narratorMeta[$item->slug] ?? null;
                                    $count = $collectionCounts[$item->id] ?? 0;
                                    $totalHadiths = $meta['total_hadiths'] ?? number_format($count);
                                    $letter = $meta['letter'] ?? mb_substr($item->name, 0, 1);
                                    $sample = isset($collectionPreviews[$item->slug]) ? $collectionPreviews[$item->slug]->first() : null;
                                @endphp
                                <button type="button" class="hadith-book-row-card" wire:key="book-card-{{ $item->id }}" wire:click="chooseCollection('{{ $item->slug }}')">
                                    <div class="book-emblem-wrap">
                                        <div class="book-emblem-diamond">
                                            <span class="book-emblem-letter">{{ $letter }}</span>
                                        </div>
                                    </div>

                                    <div class="book-info-col">
                                        <div class="book-title-row">
                                            <h3 class="book-name">{{ $item->name }}</h3>
                                            @if($item->name_ar)
                                                <span class="book-name-ar" lang="ar" dir="rtl">{{ $item->name_ar }}</span>
                                            @endif
                                        </div>
                                        <div class="book-meta-sub">
                                            <span class="book-hadith-count">Total Hadiths {{ $totalHadiths }}</span>
                                            @if(!empty($meta['author']))
                                                <span class="book-author-dot">·</span>
                                                <span class="book-author-text">{{ $meta['author'] }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="book-action-col" aria-hidden="true">
                                        <span class="book-chevron">›</span>
                                    </div>

                                    @if($sample)
                                        <span class="sr-only">{{ $sample->swahili }}</span>
                                    @endif
                                </button>
                            @endforeach
                        </div>
                    </section>
                @else
                    {{-- Inside Book / Search Results View --}}
                    @if($collection !== '')
                        @php
                            $activeCol = $collections->firstWhere('slug', $collection);
                            $activeMeta = $narratorMeta[$collection] ?? null;
                        @endphp

                        <div class="standalone-back-bar">
                            <button type="button" class="back-to-catalog-btn" wire:click="chooseCollection('')" title="Rudi kwenye vitabu vyote">
                                <span class="back-arrow" aria-hidden="true">←</span>
                                <span data-i18n="back_to_all_books">Nyuma</span>
                            </button>
                        </div>

                        @if($activeCol)
                            <div class="inside-book-banner">
                                <div class="inside-book-info">
                                    <div class="inside-book-tagline">
                                        <span class="badge badge-{{ $activeCol->slug }}">{{ $activeMeta['badge'] ?? 'Hadith' }}</span>
                                        <span class="inside-book-count">{{ number_format($collectionCounts[$activeCol->id] ?? 0) }} Hadithi zilizomo</span>
                                    </div>
                                    <h2 class="inside-book-heading">{{ $activeCol->name }}</h2>
                                    @if($activeCol->name_ar)
                                        <p class="inside-book-ar" lang="ar" dir="rtl">{{ $activeCol->name_ar }}</p>
                                    @endif
                                    @if($activeMeta)
                                        <p class="inside-book-author"><strong>Mkusanyaji:</strong> {{ $activeMeta['author'] }} @if(!empty($activeMeta['years']))({{ $activeMeta['years'] }})@endif</p>
                                        <p class="inside-book-desc">{{ $activeMeta['desc'] }}</p>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <form class="search-form book-search-form" wire:submit.prevent="$refresh" role="search" style="margin-bottom: 20px;">
                            <label class="sr-only" for="search">Tafuta hadith kwa maneno au namba</label>
                            <span class="search-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="11" cy="11" r="8"></circle>
                                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                </svg>
                            </span>
                            <input id="search" type="search" wire:model.live.debounce.350ms="search" maxlength="200" placeholder="Tafuta ndani ya {{ $activeCol ? $activeCol->name : 'hadith' }}…" autocomplete="off">
                            <button type="submit">
                                <span data-i18n="search_btn">Tafuta</span>
                                <span aria-hidden="true">→</span>
                            </button>
                        </form>
                    @endif

                    @if($search !== '')
                        <div class="search-active-banner">
                            <span class="search-active-text">
                                Matokeo ya utafutaji wa: <strong>“{{ $search }}”</strong> ({{ $hadiths->total() }} zilipatikana)
                            </span>
                            <button type="button" class="text-button" wire:click="$set('search', '')">Ondoa utafutaji ×</button>
                        </div>
                    @endif

                    @forelse($hadiths as $hadith)
                        <article class="hadith-card" wire:key="hadith-{{ $hadith->id }}">
                            <div class="card-top">
                                <span class="badge badge-{{ $hadith->chapter->book->collection->slug }}">
                                    {{ $hadith->chapter->book->collection->name }}
                                </span>
                                <span class="hadith-num">Na. {{ $hadith->number }}</span>
                            </div>
                            <h3>{{ $hadith->chapter->title_sw }}</h3>
                            
                            <div class="hadith-texts">
                                <div data-reading="ar" class="arabic-box">
                                    <p class="arabic excerpt" lang="ar" dir="rtl">{{ $hadith->arabic }}</p>
                                </div>
                                <p data-reading="sw" lang="sw" class="translation swahili-text excerpt">
                                    {!! $highlight($hadith->swahili, $search) !!}
                                </p>
                                @if($hadith->english)
                                    <p data-reading="en" lang="en" class="translation english-text excerpt">
                                        {!! $highlight($hadith->english, $search) !!}
                                    </p>
                                @endif
                            </div>

                            <p class="card-source"><span data-i18n="card_source_prefix">Chanzo: </span>{{ $hadith->source_name }}</p>
                            
                            <div class="card-bottom">
                                <span class="card-location"><span data-i18n="card_book_label">Kitabu</span> {{ $hadith->chapter->book->number }} · <span data-i18n="card_chapter_label">Mlango</span> {{ $hadith->chapter->number }}</span>
                                <div class="card-actions">
                                    <a href="{{ route('hadith.show', $hadith) }}" class="read-btn">
                                        <span data-i18n="card_read_btn">Soma</span>
                                        <span class="read-arrow" aria-hidden="true">↗</span>
                                    </a>
                                </div>
                            </div>
                        </article>
                    @empty
                        <div class="empty-state">
                            <div class="empty-icon" aria-hidden="true">▤</div>
                            <span class="section-label" data-i18n="{{ $total === 0 ? 'empty_catalog_label' : 'empty_search_label' }}">{{ $total === 0 ? 'MAKTABA INAANDALIWA' : 'JARIBU UTAFUTAJI MWINGINE' }}</span>
                            <h3 data-i18n="{{ $total === 0 ? 'empty_title_zero' : 'empty_title_search' }}">{{ $total === 0 ? 'Safari ya elimu inaanzia hapa.' : 'Hakuna hadith iliyopatikana.' }}</h3>
                            <p data-i18n="{{ $total === 0 ? 'empty_desc_zero' : 'empty_desc_search' }}">{{ $total === 0 ? 'Hadith za Kiarabu na tafsiri za Kiswahili zitaonekana hapa baada ya kuingizwa na kuhakikiwa pamoja na marejeo yake.' : 'Jaribu maneno machache, namba nyingine, au ondoa vichujio vya kitabu na mlango.' }}</p>
                            @if($total === 0)
                                <span class="status-pill"><span></span> Inasubiri maudhui yaliyohakikiwa</span>
                            @else
                                <button class="primary-button" wire:click="clearFilters" data-i18n="empty_show_all">Onyesha hadith zote →</button>
                            @endif
                        </div>
                    @endforelse

                    @if($hadiths->hasPages())
                        <nav class="pagination" aria-label="Kurasa za matokeo">
                            <button wire:click="previousPage" @disabled($hadiths->onFirstPage()) class="page-nav-btn">
                                <span>←</span> <span data-i18n="page_prev">Iliyotangulia</span>
                            </button>
                            <span class="page-indicator">Ukurasa {{ $hadiths->currentPage() }} / {{ $hadiths->lastPage() }}</span>
                            <button wire:click="nextPage" @disabled(!$hadiths->hasMorePages()) class="page-nav-btn">
                                <span data-i18n="page_next">Inayofuata</span> <span>→</span>
                            </button>
                        </nav>
                    @endif
                @endif
            </div>
        </div>
    </section>
</div>
