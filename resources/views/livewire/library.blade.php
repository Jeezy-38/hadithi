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
                                    $totalHadiths = number_format($count);
                                    $letter = $meta['letter'] ?? mb_substr($item->name, 0, 1);
                                    $sample = isset($collectionPreviews[$item->slug]) ? $collectionPreviews[$item->slug]->first() : null;
                                @endphp
                                <button type="button" class="hadith-book-row-card" wire:key="book-card-{{ $item->id }}" wire:click="chooseCollection('{{ $item->slug }}')">
                                    {{-- Left Islamic Filigree & Hanging Lantern --}}
                                    <div class="card-deco-left" aria-hidden="true">
                                        <svg class="deco-svg-left" viewBox="0 0 100 130" fill="none" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
                                            <!-- Top-Left Corner Arabesque Filigree -->
                                            <path class="filigree-stroke" d="M0,0 L38,0 C38,12 30,26 16,32 C8,35 0,36 0,36 Z" />
                                            <path class="filigree-line" d="M0,4 C14,4 28,12 32,26 C34,33 30,42 22,46 C16,49 8,46 4,40 C0,34 4,26 12,24 C20,22 28,28 30,36" stroke-width="1.2" stroke-linecap="round" />
                                            <path class="filigree-line" d="M4,0 C4,14 12,28 26,32 C33,34 42,30 46,22 C49,16 46,8 40,4 C34,0 26,4 24,12 C22,20 28,28 36,30" stroke-width="0.9" opacity="0.6" />
                                            <!-- Hanging Lantern (Fanoos) Left -->
                                            <line x1="42" y1="0" x2="42" y2="28" class="lantern-chain" stroke-width="1.2" />
                                            <circle cx="42" cy="29" r="2.2" class="lantern-ring" stroke-width="1" fill="none" />
                                            <path d="M37 33 L47 33 L44 37 L40 37 Z" class="lantern-metal" />
                                            <polygon points="38,37 46,37 49,50 42,58 35,50" class="lantern-glass" />
                                            <circle cx="42" cy="47" r="4.2" class="lantern-flame" />
                                            <line x1="42" y1="58" x2="42" y2="64" class="lantern-chain" stroke-width="1.2" />
                                            <circle cx="42" cy="65" r="1.5" class="lantern-metal" />
                                            <!-- Bottom-Left Filigree -->
                                            <path class="filigree-line" d="M0,126 C14,126 26,118 30,106 C32,98 28,90 20,86 C14,83 7,86 4,92" stroke-width="1" opacity="0.5" />
                                        </svg>
                                    </div>

                                    {{-- Medallion Badge with Initial Letter --}}
                                    <div class="book-emblem-wrap" aria-hidden="true">
                                        <svg class="medallion-svg" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path class="medallion-bg" d="M 52.33 23.58 A 10.5 10.5 0 0 1 52.33 40.42 A 10.5 10.5 0 0 1 40.42 52.33 A 10.5 10.5 0 0 1 23.58 52.33 A 10.5 10.5 0 0 1 11.67 40.42 A 10.5 10.5 0 0 1 11.67 23.58 A 10.5 10.5 0 0 1 23.58 11.67 A 10.5 10.5 0 0 1 40.42 11.67 A 10.5 10.5 0 0 1 52.33 23.58 Z" />
                                            <path class="medallion-border" d="M 52.33 23.58 A 10.5 10.5 0 0 1 52.33 40.42 A 10.5 10.5 0 0 1 40.42 52.33 A 10.5 10.5 0 0 1 23.58 52.33 A 10.5 10.5 0 0 1 11.67 40.42 A 10.5 10.5 0 0 1 11.67 23.58 A 10.5 10.5 0 0 1 23.58 11.67 A 10.5 10.5 0 0 1 40.42 11.67 A 10.5 10.5 0 0 1 52.33 23.58 Z" />
                                            <text class="medallion-letter" x="32" y="38" text-anchor="middle" dominant-baseline="middle">{{ $letter }}</text>
                                        </svg>
                                    </div>

                                    {{-- Book Information --}}
                                    <div class="book-info-col">
                                        <div class="book-title-row">
                                            <h3 class="book-name">{{ $item->name }}</h3>
                                            @if($item->name_ar)
                                                <span class="book-star-sep" aria-hidden="true">❖</span>
                                                <span class="book-name-ar" lang="ar" dir="rtl">{{ $item->name_ar }}</span>
                                                <span class="book-star-sep" aria-hidden="true">❖</span>
                                            @endif
                                        </div>
                                        <div class="book-meta-sub">
                                            <div class="book-meta-item">
                                                <svg class="book-meta-icon" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                    <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
                                                </svg>
                                                <span class="book-hadith-count">Total Hadiths {{ $totalHadiths }}</span>
                                            </div>
                                            @if(!empty($meta['author']))
                                                <span class="book-meta-divider" aria-hidden="true">|</span>
                                                <div class="book-meta-item">
                                                    <svg class="book-meta-icon" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                                    </svg>
                                                    <span class="book-author-text">{{ $meta['author'] }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Right Mosque Silhouette, Crescent Moon, Lanterns & Filigree --}}
                                    <div class="card-deco-right" aria-hidden="true">
                                        <svg class="deco-svg-right" viewBox="0 0 460 130" preserveAspectRatio="xMaxYMid meet" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <!-- Secondary/Background Dome & Arch elements -->
                                            <path class="mosque-silhouette-bg" d="M120 130 L120 120 C140 120 150 110 160 95 C170 80 185 75 195 75 C205 75 220 80 230 95 C240 110 250 120 270 120 L270 130 Z" />

                                            <!-- Left Minaret -->
                                            <rect x="226" y="55" width="12" height="75" class="mosque-silhouette" />
                                            <path d="M222 55 L242 55 L240 50 L224 50 Z" class="mosque-silhouette" />
                                            <rect x="227" y="32" width="10" height="18" class="mosque-silhouette" />
                                            <polygon points="232,15 226,32 238,32" class="mosque-silhouette" />
                                            <line x1="232" y1="15" x2="232" y2="10" class="mosque-metal" stroke-width="1" />
                                            <circle cx="232" cy="9" r="1.5" class="finial-crescent" />
                                            <path d="M230 40 Q232 37 234 40 L234 45 L230 45 Z" class="mosque-window" />
                                            <path d="M229 70 Q232 67 235 70 L235 77 L229 77 Z" class="mosque-window" />

                                            <!-- Main Grand Dome -->
                                            <rect x="252" y="88" width="86" height="42" class="mosque-silhouette" />
                                            <path class="mosque-silhouette" d="M252 88 C248 72 262 50 280 40 C288 35 293 30 295 24 C297 30 302 35 310 40 C328 50 342 72 338 88 Z" />
                                            <line x1="295" y1="24" x2="295" y2="14" class="mosque-metal" stroke-width="1.2" />
                                            <path d="M294 14 C296.5 14 298.5 12 298.5 9.5 C298.5 7 296.5 5 294 5 C295.5 6 296.5 7.5 296.5 9.5 C296.5 11.5 295.5 13 294 14 Z" class="finial-crescent" />
                                            <!-- Arched Windows in Dome Base -->
                                            <path d="M266 100 Q270 95 274 100 L274 112 L266 112 Z" class="mosque-window" />
                                            <path d="M280 97 Q285 92 290 97 L290 112 L280 112 Z" class="mosque-window" />
                                            <path d="M296 97 Q301 92 306 97 L306 112 L296 112 Z" class="mosque-window" />
                                            <path d="M312 100 Q316 95 320 100 L320 112 L312 112 Z" class="mosque-window" />

                                            <!-- Right Minaret -->
                                            <rect x="348" y="58" width="12" height="72" class="mosque-silhouette" />
                                            <path d="M344 58 L364 58 L362 53 L346 53 Z" class="mosque-silhouette" />
                                            <rect x="349" y="36" width="10" height="17" class="mosque-silhouette" />
                                            <polygon points="354,20 348,36 360,36" class="mosque-silhouette" />
                                            <line x1="354" y1="20" x2="354" y2="14" class="mosque-metal" stroke-width="1" />
                                            <circle cx="354" cy="13" r="1.5" class="finial-crescent" />
                                            <path d="M352 43 Q354 40 356 43 L356 48 L352 48 Z" class="mosque-window" />
                                            <path d="M351 74 Q354 71 357 74 L357 81 L351 81 Z" class="mosque-window" />

                                            <!-- Crescent Moon in Sky -->
                                            <path class="sky-crescent" d="M268 28 C275 28 281 22 281 14 C281 11.5 280.3 9.2 279 7.2 C285 9 289 14.5 289 21 C289 29.3 282.3 36 274 36 C270.5 36 267.3 34.8 264.7 32.8 C265.7 32.9 266.8 33 268 33 C272 33 275.5 31 268 28 Z" />

                                            <!-- Hanging Lanterns (Fanoos) Right -->
                                            <!-- Lantern 1 -->
                                            <line x1="206" y1="0" x2="206" y2="34" class="lantern-chain" stroke-width="1.2" />
                                            <circle cx="206" cy="35" r="2" class="lantern-ring" stroke-width="1" fill="none" />
                                            <path d="M202 38 L210 38 L208 41 L204 41 Z" class="lantern-metal" />
                                            <polygon points="203,41 209,41 212,50 206,56 200,50" class="lantern-glass" />
                                            <circle cx="206" cy="48" r="3.5" class="lantern-flame" />
                                            <line x1="206" y1="56" x2="206" y2="61" class="lantern-chain" stroke-width="1" />
                                            <circle cx="206" cy="62" r="1.2" class="lantern-metal" />

                                            <!-- Lantern 2 -->
                                            <line x1="395" y1="0" x2="395" y2="24" class="lantern-chain" stroke-width="1.2" />
                                            <circle cx="395" cy="25" r="2.2" class="lantern-ring" stroke-width="1" fill="none" />
                                            <path d="M390 28 L400 28 L397 32 L393 32 Z" class="lantern-metal" />
                                            <polygon points="391,32 399,32 402,44 395,51 388,44" class="lantern-glass" />
                                            <circle cx="395" cy="42" r="4" class="lantern-flame" />
                                            <line x1="395" y1="51" x2="395" y2="57" class="lantern-chain" stroke-width="1" />
                                            <circle cx="395" cy="58" r="1.4" class="lantern-metal" />

                                            <!-- Top-Right & Bottom-Right Corner Arabesque Filigree -->
                                            <path class="filigree-line" d="M460,4 C446,4 432,12 428,26 C426,33 430,42 438,46 C444,49 452,46 456,40" stroke-width="1.2" stroke-linecap="round" />
                                            <path class="filigree-line" d="M456,0 C456,14 448,28 434,32 C427,34 418,30 414,22 C411,16 414,8 420,4" stroke-width="0.9" opacity="0.6" />
                                            <path class="filigree-line" d="M460,126 C446,126 434,118 430,106 C428,98 432,90 440,86" stroke-width="1" opacity="0.5" />
                                        </svg>
                                    </div>

                                    {{-- Right Navigation Arrow --}}
                                    <div class="book-action-col" aria-hidden="true">
                                        <svg class="book-chevron-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="9 18 15 12 9 6"></polyline>
                                        </svg>
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
