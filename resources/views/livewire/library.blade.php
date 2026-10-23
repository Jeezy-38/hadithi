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
                                        <svg class="deco-svg-left" viewBox="0 0 80 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <!-- Top-Left Corner Arabesque Filigree -->
                                            <path class="filigree-line" d="M0,2 C12,2 24,8 28,18 C30,23 27,29 20,32 C15,34 8,32 5,27 C2,22 5,17 10,16 C15,15 21,19 22,24" stroke-width="1.2" stroke-linecap="round" />
                                            <path class="filigree-line" d="M2,0 C2,12 8,24 18,28 C23,30 29,27 32,20 C34,15 32,8 27,5 C22,2 17,5 16,10 C15,15 19,21 24,22" stroke-width="0.9" opacity="0.6" />
                                            
                                            <!-- Hanging Golden Lantern (Fanoos) Left -->
                                            <line x1="38" y1="0" x2="38" y2="24" class="lantern-chain" stroke-width="1.2" />
                                            <circle cx="38" cy="25" r="2" class="lantern-ring" stroke-width="1" fill="none" />
                                            <path d="M33 28 L43 28 L41 31 L35 31 Z" class="lantern-metal" />
                                            <polygon points="34,31 42,31 45,43 38,51 31,43" class="lantern-glass" />
                                            <circle cx="38" cy="40" r="3.5" class="lantern-flame" />
                                            <line x1="38" y1="51" x2="38" y2="56" class="lantern-chain" stroke-width="1" />
                                            <circle cx="38" cy="57" r="1.2" class="lantern-metal" />

                                            <!-- Bottom-Left Filigree -->
                                            <path class="filigree-line" d="M0,118 C12,118 22,112 25,102 C27,96 24,90 18,87" stroke-width="1" opacity="0.4" />
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
                                                <span class="book-star-sep book-star-desktop" aria-hidden="true">❖</span>
                                                <span class="book-name-ar" lang="ar" dir="rtl"><span class="book-star-sep book-star-mobile" aria-hidden="true">❖</span> {{ $item->name_ar }} <span class="book-star-sep book-star-mobile" aria-hidden="true">❖</span></span>
                                                <span class="book-star-sep book-star-desktop" aria-hidden="true">❖</span>
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
                                                <div class="book-meta-item book-author-item">
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
                                        <svg class="deco-svg-right" viewBox="0 0 380 125" preserveAspectRatio="xMaxYMid meet" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <!-- Background Arch/Dome Elements -->
                                            <path class="mosque-silhouette-bg" d="M80 125 C105 125 115 110 130 96 C145 82 165 76 180 76 C195 76 215 82 230 96 C245 110 255 125 280 125 Z" />

                                            <!-- Left Minaret -->
                                            <rect x="186" y="48" width="10" height="77" class="mosque-silhouette" />
                                            <path d="M182 48 L200 48 L198 44 L184 44 Z" class="mosque-silhouette" />
                                            <rect x="187" y="28" width="8" height="16" class="mosque-silhouette" />
                                            <polygon points="191,14 186,28 196,28" class="mosque-silhouette" />
                                            <line x1="191" y1="14" x2="191" y2="8" class="mosque-metal" stroke-width="1" />
                                            <circle cx="191" cy="7" r="1.3" class="finial-crescent" />
                                            <path d="M189 35 Q191 32 193 35 L193 40 L189 40 Z" class="mosque-window" />
                                            <path d="M189 62 Q191 59 193 62 L193 68 L189 68 Z" class="mosque-window" />

                                            <!-- Main Grand Dome -->
                                            <rect x="210" y="80" width="76" height="45" class="mosque-silhouette" />
                                            <path class="mosque-silhouette" d="M210 80 C206 64 218 45 236 36 C244 32 247 27 248 22 C249 27 252 32 260 36 C278 45 290 64 286 80 Z" />
                                            <line x1="248" y1="22" x2="248" y2="12" class="mosque-metal" stroke-width="1.2" />
                                            <path d="M247 12 C249.5 12 251.5 10 251.5 7.5 C251.5 5 249.5 3 247 3 C248.5 4 249.5 5.5 249.5 7.5 C249.5 9.5 248.5 11 247 12 Z" class="finial-crescent" />
                                            <!-- Windows in Dome Base -->
                                            <path d="M222 92 Q225 87 228 92 L228 104 L222 104 Z" class="mosque-window" />
                                            <path d="M234 89 Q238 84 242 89 L242 104 L234 104 Z" class="mosque-window" />
                                            <path d="M248 89 Q252 84 256 89 L256 104 L248 104 Z" class="mosque-window" />
                                            <path d="M262 92 Q265 87 268 92 L268 104 L262 104 Z" class="mosque-window" />

                                            <!-- Right Minaret -->
                                            <rect x="296" y="52" width="10" height="73" class="mosque-silhouette" />
                                            <path d="M292 52 L310 52 L308 48 L294 48 Z" class="mosque-silhouette" />
                                            <rect x="297" y="32" width="8" height="16" class="mosque-silhouette" />
                                            <polygon points="301,18 296,32 306,32" class="mosque-silhouette" />
                                            <line x1="301" y1="18" x2="301" y2="12" class="mosque-metal" stroke-width="1" />
                                            <circle cx="301" cy="11" r="1.3" class="finial-crescent" />
                                            <path d="M299 38 Q301 35 303 38 L303 43 L299 43 Z" class="mosque-window" />
                                            <path d="M299 66 Q301 63 303 66 L303 72 L299 72 Z" class="mosque-window" />

                                            <!-- Crescent Moon in Sky -->
                                            <path class="sky-crescent" d="M228 24 C234 24 239 19 239 12 C239 10 238.4 8 237.3 6.3 C242 8 245 12.5 245 18 C245 25 239.5 30.5 232.5 30.5 C229.5 30.5 226.8 29.5 224.6 27.8 C225.5 27.9 226.4 28 227.4 28 C230.8 28 233.7 26.3 228 24 Z" />

                                            <!-- Hanging Lanterns (Fanoos) Right -->
                                            <!-- Lantern 1 (Mid-right) -->
                                            <line x1="168" y1="0" x2="168" y2="30" class="lantern-chain" stroke-width="1.2" />
                                            <circle cx="168" cy="31" r="1.8" class="lantern-ring" stroke-width="1" fill="none" />
                                            <path d="M164 34 L172 34 L170 37 L166 37 Z" class="lantern-metal" />
                                            <polygon points="165,37 171,37 173,46 168,52 163,46" class="lantern-glass" />
                                            <circle cx="168" cy="44" r="3" class="lantern-flame" />
                                            <line x1="168" y1="52" x2="168" y2="56" class="lantern-chain" stroke-width="1" />
                                            <circle cx="168" cy="57" r="1" class="lantern-metal" />

                                            <!-- Lantern 2 (Near outer edge) -->
                                            <line x1="334" y1="0" x2="334" y2="20" class="lantern-chain" stroke-width="1.2" />
                                            <circle cx="334" cy="21" r="2" class="lantern-ring" stroke-width="1" fill="none" />
                                            <path d="M330 24 L338 24 L336 27 L332 27 Z" class="lantern-metal" />
                                            <polygon points="331,27 337,27 340,38 334,44 328,38" class="lantern-glass" />
                                            <circle cx="334" cy="35" r="3.2" class="lantern-flame" />
                                            <line x1="334" y1="44" x2="334" y2="49" class="lantern-chain" stroke-width="1" />
                                            <circle cx="334" cy="50" r="1.2" class="lantern-metal" />

                                            <!-- Top-Right & Bottom-Right Corner Arabesque Filigree -->
                                            <path class="filigree-line" d="M380,2 C368,2 356,8 352,18 C350,23 353,29 360,32" stroke-width="1.2" stroke-linecap="round" />
                                            <path class="filigree-line" d="M378,0 C378,12 372,24 362,28" stroke-width="0.9" opacity="0.6" />
                                            <path class="filigree-line" d="M380,118 C368,118 358,112 355,102" stroke-width="1" opacity="0.4" />
                                        </svg>
                                    </div>

                                    {{-- Right Navigation Arrow --}}
                                    <div class="book-action-col" aria-hidden="true">
                                        <div class="book-action-circle">
                                            <svg class="book-chevron-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="9 18 15 12 9 6"></polyline>
                                            </svg>
                                        </div>
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
