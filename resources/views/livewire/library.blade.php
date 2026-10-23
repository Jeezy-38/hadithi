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
                    {{-- =======================================================
                         ISLAMIC BENTO DASHBOARD (MTINDO 1)
                         ======================================================= --}}
                    <section class="islamic-bento-section" aria-label="Islamic Bento Dashboard">
                        <div class="bento-section-header">
                            <div class="bento-header-info">
                                <span class="bento-eyebrow-pill">
                                    <span class="bento-pulse-dot" aria-hidden="true"></span>
                                    <span>DASHBOARD YA KIISLAMU · MTINDO WA KISASA</span>
                                </span>
                                <h2 class="bento-section-title">Kitovu cha Sunnah &amp; Bento Dashboard</h2>
                                <p class="bento-section-desc">
                                    Maudhui ya kiroho ya kila siku, mada 12 za maisha, na mkusanyiko kamili wa Hadithi sahihi {{ number_format($total) }}.
                                </p>
                            </div>
                            <div class="bento-header-badges">
                                <span class="bento-counter-pill">
                                    <span class="pill-dot"></span> {{ number_format($total) }} Hadithi Zilizohakikiwa
                                </span>
                            </div>
                        </div>

                        <div class="islamic-bento-grid">
                            {{-- 1. Giant Hero Bento Card: Hadith ya Leo (Span 8) --}}
                            @if($todayHadith)
                                <div class="bento-card bento-card-hero">
                                    <div class="bento-hero-bg-glow" aria-hidden="true"></div>
                                    <div class="bento-hero-watermark" aria-hidden="true">
                                        <svg viewBox="0 0 100 100" width="160" height="160" fill="none">
                                            <circle cx="50" cy="50" r="46" stroke="currentColor" stroke-width="1" stroke-dasharray="3 3"/>
                                            <polygon points="50,10 62,38 90,50 62,62 50,90 38,62 10,50 38,38" stroke="currentColor" stroke-width="1.2"/>
                                            <circle cx="50" cy="50" r="22" stroke="currentColor" stroke-width="0.8"/>
                                        </svg>
                                    </div>

                                    <div class="bento-card-inner">
                                        <div class="bento-hero-top-bar">
                                            <div class="bento-hero-tag">
                                                <span class="tag-star">✦</span>
                                                <span>Hadithi ya Leo</span>
                                            </div>
                                            <div class="bento-hero-ref">
                                                <span>{{ $todayHadith->chapter->book->collection->name ?? 'Hadithi' }}</span>
                                                <span class="ref-sep">·</span>
                                                <span>Na. {{ $todayHadith->hadith_number ?? $todayHadith->number ?? $todayHadith->reference }}</span>
                                            </div>
                                        </div>

                                        {{-- Arabic Calligraphy --}}
                                        <div class="bento-arabic-block" dir="rtl" lang="ar">
                                            <span class="bento-arabic-text">{{ $todayHadith->arabic }}</span>
                                        </div>

                                        {{-- Swahili Translation --}}
                                        <div class="bento-swahili-block">
                                            <p class="bento-swahili-text">
                                                "{{ Str::limit($todayHadith->swahili, 260) }}"
                                            </p>
                                        </div>

                                        {{-- Interactive Audio & Action Bar --}}
                                        <div class="bento-hero-footer">
                                            <div class="bento-audio-strip" id="bento-audio-strip" data-arabic="{{ e($todayHadith->arabic) }}" data-swahili="{{ e($todayHadith->swahili) }}">
                                                <button type="button" class="bento-audio-play-btn" id="bento-audio-toggle" aria-label="Sikiliza Kisomo cha Hadithi" title="Sikiliza Kisomo cha Sauti">
                                                    <svg class="icon-play" viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
                                                        <path d="M8 5v14l11-7z"/>
                                                    </svg>
                                                    <svg class="icon-pause" viewBox="0 0 24 24" width="16" height="16" fill="currentColor" style="display: none;">
                                                        <path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/>
                                                    </svg>
                                                </button>
                                                <div class="bento-audio-info">
                                                    <span class="bento-audio-label">Sikiliza Kisomo cha Hadithi</span>
                                                    <span class="bento-audio-reciter">Kisomo Rasmi · Web Audio</span>
                                                </div>
                                                <div class="bento-audio-waves" id="bento-audio-waves" aria-hidden="true">
                                                    <span class="wave-bar bar-1"></span>
                                                    <span class="wave-bar bar-2"></span>
                                                    <span class="wave-bar bar-3"></span>
                                                    <span class="wave-bar bar-4"></span>
                                                    <span class="wave-bar bar-5"></span>
                                                </div>
                                            </div>

                                            <div class="bento-hero-actions">
                                                <a href="{{ route('hadith.show', $todayHadith) }}" class="bento-btn-primary">
                                                    <span>Soma Kamili</span>
                                                    <svg viewBox="0 0 20 20" width="14" height="14" fill="currentColor" aria-hidden="true">
                                                        <path fill-rule="evenodd" d="M5.22 14.78a.75.75 0 001.06 0l7.22-7.22v5.69a.75.75 0 001.5 0v-7.5a.75.75 0 00-.75-.75h-7.5a.75.75 0 000 1.5h5.69l-7.22 7.22a.75.75 0 000 1.06z" clip-rule="evenodd" />
                                                    </svg>
                                                </a>

                                                <button type="button" class="bento-btn-icon" id="bento-copy-btn" title="Nakili hadithi" data-copy="{{ $todayHadith->swahili }} — {{ $todayHadith->arabic }} ({{ $todayHadith->reference }})">
                                                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                                                        <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                                                    </svg>
                                                </button>

                                                @php
                                                    $shareText = urlencode("Hadithi ya Leo kutoka Bayt Al-Hadith:\n\n\"" . Str::limit($todayHadith->swahili, 200) . "\"\n\nSoma zaidi: " . route('hadith.show', $todayHadith));
                                                @endphp
                                                <a href="https://wa.me/?text={{ $shareText }}" target="_blank" rel="noopener noreferrer" class="bento-btn-icon" title="Shiriki WhatsApp">
                                                    <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
                                                        <path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.816 9.816 0 0012.04 2zm5.78 14.28c-.24.68-1.39 1.3-1.92 1.38-.49.08-.94.12-2.73-.61-2.28-.93-3.76-3.23-3.87-3.38-.11-.15-.93-1.24-.93-2.37s.59-1.68.8-1.91c.21-.23.46-.29.61-.29.15 0 .31 0 .44.01.14.01.33-.05.51.39.19.46.65 1.58.71 1.7.06.12.09.26.02.41-.08.15-.12.24-.23.38-.12.14-.25.31-.35.42-.12.12-.24.25-.1.49.14.24.63 1.04 1.35 1.68.93.83 1.71 1.08 1.95 1.2.24.12.38.1.52-.06.14-.17.61-.71.77-.95.16-.24.33-.2.55-.12.22.08 1.41.67 1.65.79.24.12.4.18.46.28.06.11.06.63-.18 1.31z"/>
                                                    </svg>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            {{-- 2. Side Stack Bento Cards (Span 4) --}}
                            <div class="bento-side-stack">
                                {{-- Stat Card --}}
                                <div class="bento-card bento-card-stat">
                                    <div class="bento-stat-top">
                                        <span class="bento-stat-eyebrow">MAKTABA KAMILI</span>
                                        <div class="bento-stat-badge-emblem" aria-hidden="true">
                                            <span class="badge-emblem-star">✦</span>
                                        </div>
                                    </div>
                                    <div class="bento-stat-body">
                                        <div class="bento-stat-number">{{ number_format($total) }}</div>
                                        <div class="bento-stat-label">Hadithi Zilizohakikiwa</div>
                                        <p class="bento-stat-note">Kutoka vitabu 9 vikuu vya Sunnah (al-Kutub as-Sittah na vinginevyo).</p>
                                    </div>
                                    <div class="bento-stat-footer">
                                        <a href="#hadith-catalog-list" class="bento-stat-link">
                                            <span>Vinjari Vitabu Vyote 9</span>
                                            <span aria-hidden="true">↓</span>
                                        </a>
                                    </div>
                                </div>

                                {{-- Dhikr / Tasbih Card --}}
                                <div class="bento-card bento-card-dhikr" id="bento-dhikr-widget" style="cursor: pointer;" title="Gusa kuhesabu Tasbih">
                                    <div class="bento-dhikr-header">
                                        <div class="bento-dhikr-tag">
                                            <span class="dhikr-icon">📿</span>
                                            <span>Dhikri ya Siku</span>
                                        </div>
                                        <span class="bento-dhikr-counter" id="bento-dhikr-counter">0 / 33</span>
                                    </div>

                                    <div class="bento-dhikr-body">
                                        <div class="bento-dhikr-arabic" dir="rtl" lang="ar">
                                            سُبْحَانَ اللَّهِ وَبِحَمْدِهِ
                                        </div>
                                        <div class="bento-dhikr-swahili">
                                            "Ametakasika Allah na sifa zake zote njema"
                                        </div>
                                    </div>

                                    <div class="bento-dhikr-footer">
                                        <a href="{{ route('tasbih') }}" class="bento-dhikr-btn">
                                            <span>📿 Fungua Tasbih Kamili</span>
                                            <span aria-hidden="true">→</span>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            {{-- 3. Topics Hub Bento Card (Span 12) --}}
                            <div class="bento-card bento-card-topics">
                                <div class="bento-topics-top">
                                    <div class="bento-topics-heading">
                                        <div class="bento-topics-badge">✦ MAFUNZO NA MADA</div>
                                        <h3 class="bento-topics-title">Mada za Kiislamu (Thematic Hub)</h3>
                                        <p class="bento-topics-desc">Gundua Hadithi zilizochambuliwa kulingana na mada unayohitaji katika maisha yako.</p>
                                    </div>
                                    <a href="{{ route('topics.index') }}" class="bento-topics-more-link">
                                        <span>Tazama Mada Zote 12</span>
                                        <span aria-hidden="true">→</span>
                                    </a>
                                </div>

                                <div class="bento-topics-pills-grid">
                                    @php
                                        $topPills = array_slice($topics, 0, 8, true);
                                    @endphp
                                    @foreach($topPills as $tKey => $t)
                                        <a href="{{ route('topics.show', $t['slug']) }}" class="bento-topic-pill">
                                            <span class="pill-dot" style="background-color: {{ $t['color'] ?? 'var(--gold)' }};"></span>
                                            <span class="pill-title">{{ $t['title_sw'] }}</span>
                                            <span class="pill-ar" dir="rtl" lang="ar">{{ $t['title_ar'] }}</span>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- Collections Catalog View --}}
                    <section id="hadith-catalog-list" class="hadith-books-catalog" aria-label="Collections">
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
                                    <h2 class="hadith-books-title">Collections</h2>
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

    <script>
        (function() {
            function initBentoDashboard() {
                const playBtn = document.getElementById('bento-audio-toggle');
                const audioStrip = document.getElementById('bento-audio-strip');
                const copyBtn = document.getElementById('bento-copy-btn');
                const dhikrWidget = document.getElementById('bento-dhikr-widget');
                const dhikrCounter = document.getElementById('bento-dhikr-counter');

                if (playBtn && audioStrip && !playBtn.dataset.bound) {
                    playBtn.dataset.bound = '1';
                    let isSpeaking = false;
                    playBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        if (!('speechSynthesis' in window)) {
                            alert('Kifaa chako hakiauni usomaji wa sauti (Speech Synthesis).');
                            return;
                        }
                        if (isSpeaking) {
                            window.speechSynthesis.cancel();
                            isSpeaking = false;
                            audioStrip.classList.remove('is-playing');
                            const p = playBtn.querySelector('.icon-play');
                            const pa = playBtn.querySelector('.icon-pause');
                            if (p) p.style.display = 'block';
                            if (pa) pa.style.display = 'none';
                        } else {
                            window.speechSynthesis.cancel();
                            const arabic = audioStrip.getAttribute('data-arabic') || '';
                            const swahili = audioStrip.getAttribute('data-swahili') || '';
                            const text = arabic || swahili;
                            const utterance = new SpeechSynthesisUtterance(text);
                            utterance.lang = arabic ? 'ar-SA' : 'sw-TZ';
                            utterance.rate = 0.85;

                            utterance.onstart = function() {
                                isSpeaking = true;
                                audioStrip.classList.add('is-playing');
                                const p = playBtn.querySelector('.icon-play');
                                const pa = playBtn.querySelector('.icon-pause');
                                if (p) p.style.display = 'none';
                                if (pa) pa.style.display = 'block';
                            };
                            utterance.onend = utterance.onerror = function() {
                                isSpeaking = false;
                                audioStrip.classList.remove('is-playing');
                                const p = playBtn.querySelector('.icon-play');
                                const pa = playBtn.querySelector('.icon-pause');
                                if (p) p.style.display = 'block';
                                if (pa) pa.style.display = 'none';
                            };
                            window.speechSynthesis.speak(utterance);
                        }
                    });
                }

                if (copyBtn && !copyBtn.dataset.bound) {
                    copyBtn.dataset.bound = '1';
                    copyBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        const text = copyBtn.getAttribute('data-copy');
                        if (navigator.clipboard) {
                            navigator.clipboard.writeText(text).then(function() {
                                const originalHTML = copyBtn.innerHTML;
                                copyBtn.innerHTML = '<span style="color:var(--gold);font-weight:900;">✓</span>';
                                setTimeout(function() {
                                    copyBtn.innerHTML = originalHTML;
                                }, 2000);
                            });
                        }
                    });
                }

                if (dhikrWidget && dhikrCounter && !dhikrWidget.dataset.bound) {
                    dhikrWidget.dataset.bound = '1';
                    let dhikrCount = 0;
                    dhikrWidget.addEventListener('click', function(e) {
                        if (e.target.closest('a')) return;
                        dhikrCount = (dhikrCount + 1) % 34;
                        dhikrCounter.textContent = dhikrCount + ' / 33';
                        dhikrWidget.style.transform = 'scale(0.98)';
                        setTimeout(function() { dhikrWidget.style.transform = ''; }, 100);
                        if (navigator.vibrate) navigator.vibrate(25);
                    });
                }
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initBentoDashboard);
            } else {
                initBentoDashboard();
            }
            document.addEventListener('livewire:navigated', initBentoDashboard);
        })();
    </script>
</div>
