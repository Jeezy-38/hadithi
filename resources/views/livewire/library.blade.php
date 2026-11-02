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
            <div class="hero-media" aria-hidden="true">
                <img src="{{ asset('images/hero-1200.jpg') }}" srcset="{{ asset('images/hero-640.jpg') }} 640w, {{ asset('images/hero-1200.jpg') }} 1200w" sizes="(max-width: 860px) 100vw, 55vw" alt="" width="1200" height="675" decoding="async" fetchpriority="high">
            </div>
            <div class="hero-copy">
                <h1 data-i18n="hero_h1">BAYT UL <em>HADITH</em></h1>
                <p class="hero-meaning" data-i18n="pg_hero_meaning">بيت الحديث — "Nyumba ya Hadith"</p>
                <ul class="hero-chips">
                    <li data-i18n="pg_hero_c1">Hadith sahihi</li>
                    <li data-i18n="pg_hero_c2">Chanzo na daraja</li>
                    <li>Kiswahili · English · العربية</li>
                </ul>
            </div>
        </section>
    @endif

    <section id="maktaba" class="library-shell" aria-label="Maktaba ya hadith">
        <div class="results">
                        <form class="search-form book-search-form library-search" wire:submit.prevent="$refresh" role="search">
                            <label class="sr-only" for="search" data-i18n="pg_lib_search_label">Tafuta hadith kwa maneno au namba</label>
                            <span class="search-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="11" cy="11" r="8"></circle>
                                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                </svg>
                            </span>
                            <input id="search" type="search" wire:model.live.debounce.350ms="search" maxlength="200" placeholder="Tafuta ndani ya {{ $collections->firstWhere('slug', $collection)?->name ?? 'makusanyo yote' }}…" data-i18n-placeholder="{{ $collection === '' ? 'pg_lib_search_all' : 'pg_lib_search_in' }}" data-i18n-vars="{{ json_encode(['name' => $collections->firstWhere('slug', $collection)?->name ?? ''], JSON_UNESCAPED_UNICODE) }}" autocomplete="off">
                            <button type="submit">
                                <span data-i18n="search_btn">Tafuta</span>
                                <span aria-hidden="true">→</span>
                            </button>
                        </form>

            <div class="loading" wire:loading role="status">
                <span class="spinner" aria-hidden="true"></span>
                <span data-i18n="searching_status">Inatafuta…</span>
            </div>

            <div wire:loading.class="is-loading">
                @if($collection === '' && $search === '' && $hadiths->currentPage() === 1)
                    <div class="rawdah-layout-container">
                        {{-- 1. Hero Mehrab Spotlight Card --}}
                        @if($todayHadith)
                            <section class="rawdah-mehrab-hero" aria-label="Hadithi ya Leo">
                                <div class="rawdah-arch-watermark" aria-hidden="true">
                                    <svg viewBox="0 0 120 120" width="180" height="180" fill="none">
                                        <path d="M10 110 V50 Q10 10 60 10 Q110 10 110 50 V110" stroke="currentColor" stroke-width="1.2" stroke-dasharray="3 3"/>
                                        <circle cx="60" cy="50" r="28" stroke="currentColor" stroke-width="1"/>
                                        <polygon points="60,28 67,43 82,45 71,56 74,71 60,63 46,71 49,56 38,45 53,43" fill="none" stroke="currentColor" stroke-width="1"/>
                                    </svg>
                                </div>

                                <div class="rawdah-hero-top">
                                    <span class="rawdah-hero-tag">
                                        <span>✦</span>
                                        <span data-i18n="pg_lib_today">Hadithi ya Leo</span>
                                    </span>
                                    <span class="rawdah-hero-ref">
                                        {{ $todayHadith->chapter->book->collection->name ?? 'Hadithi' }} · Na. {{ $todayHadith->hadith_number ?? $todayHadith->number ?? $todayHadith->reference }}
                                    </span>
                                </div>

                                {{-- Arabic Calligraphy --}}
                                <div class="rawdah-arabic-box" dir="rtl" lang="ar">
                                    <span class="rawdah-arabic-text">{{ $todayHadith->arabic }}</span>
                                </div>

                                {{-- Swahili Translation --}}
                                <div class="rawdah-swahili-box">
                                    <p class="rawdah-swahili-text">
                                        "{{ Str::limit($todayHadith->swahili, 280) }}"
                                    </p>
                                </div>

                                {{-- Mdhamini wa Leo --}}
                                @if (! empty($sponsor))
                                    <a class="rawdah-sponsor" href="{{ route('sponsors.go', $sponsor) }}" target="_blank" rel="sponsored noopener">
                                        <span class="rawdah-sponsor-label" data-i18n="pg_sp_by">Imedhaminiwa na</span>
                                        @if ($sponsor->logo_url)
                                            <img class="rawdah-sponsor-logo" src="{{ $sponsor->logo_url }}" alt="" loading="lazy" width="28" height="28">
                                        @else
                                            <span class="rawdah-sponsor-logo rawdah-sponsor-initial" aria-hidden="true">{{ $sponsor->initial }}</span>
                                        @endif
                                        <span class="rawdah-sponsor-name">{{ $sponsor->name }}</span>
                                        @if ($sponsor->tagline)<span class="rawdah-sponsor-tag">— {{ $sponsor->tagline }}</span>@endif
                                    </a>
                                @else
                                    <a class="rawdah-sponsor rawdah-sponsor-invite" href="{{ route('sponsors') }}">
                                        <span data-i18n="pg_sp_invite">Nafasi ya mdhamini wa leo ipo wazi — tangaza hapa</span>
                                    </a>
                                @endif

                                {{-- Action Bar with Quick Links --}}
                                <div class="rawdah-hero-footer">
                                    <div class="rawdah-hero-buttons">
                                        <a href="{{ route('hadith.show', $todayHadith) }}" class="rawdah-btn-primary">
                                            <span data-i18n="pg_lib_read_full">Soma Hadithi Kamili</span>
                                            <span aria-hidden="true">→</span>
                                        </a>

                                        <button type="button" class="rawdah-btn-icon" id="rawdah-copy-btn" title="Nakili hadithi" data-copy="{{ $todayHadith->swahili }} — {{ $todayHadith->arabic }} ({{ $todayHadith->reference }})">
                                            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                                                <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                                            </svg>
                                        </button>

                                        @php
                                            $shareText = urlencode("Hadithi ya Leo kutoka Bayt Ul Hadith:\n\n\"" . Str::limit($todayHadith->swahili, 220) . "\"\n\nSoma zaidi: " . route('hadith.show', $todayHadith));
                                        @endphp
                                        <a href="https://wa.me/?text={{ $shareText }}" target="_blank" rel="noopener noreferrer" class="rawdah-btn-icon" title="Shiriki WhatsApp">
                                            <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
                                                <path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.816 9.816 0 0012.04 2zm5.78 14.28c-.24.68-1.39 1.3-1.92 1.38-.49.08-.94.12-2.73-.61-2.28-.93-3.76-3.23-3.87-3.38-.11-.15-.93-1.24-.93-2.37s.59-1.68.8-1.91c.21-.23.46-.29.61-.29.15 0 .31 0 .44.01.14.01.33-.05.51.39.19.46.65 1.58.71 1.7.06.12.09.26.02.41-.08.15-.12.24-.23.38-.12.14-.25.31-.35.42-.12.12-.24.25-.1.49.14.24.63 1.04 1.35 1.68.93.83 1.71 1.08 1.95 1.2.24.12.38.1.52-.06.14-.17.61-.71.77-.95.16-.24.33-.2.55-.12.22.08 1.41.67 1.65.79.24.12.4.18.46.28.06.11.06.63-.18 1.31z"/>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </section>
                        @endif

                        <div class="stats-strip" role="group" aria-label="Takwimu">
                            <div><b>{{ number_format($total) }}</b><span data-i18n="pg_st_hadith">Hadith</span></div>
                            <div><b>{{ $collections->count() }}</b><span data-i18n="pg_st_cols">Makusanyo</span></div>
                            <div><b>3</b><span data-i18n="pg_st_langs">Lugha</span></div>
                        </div>

                        {{-- 3. The Gilded Bookshelf: Collections --}}
                        <section id="makusanyo" class="gilded-bookshelf-section" aria-label="Collections za Vitabu">
                            <div class="bookshelf-header">
                                <div class="bookshelf-title-wrap">
                                    <div class="hadith-header-art" aria-hidden="true">
                                        <svg viewBox="0 0 36 36" width="32" height="32" fill="none">
                                            <rect x="7" y="4" width="23" height="28" rx="3.5" fill="#092c3d" stroke="#cbd5e1" stroke-width="1.4"/>
                                            <path d="M11 4V32" stroke="#04141d" stroke-width="2"/>
                                            <path d="M12 4V32" stroke="#e2e8f0" stroke-width="0.8"/>
                                            <path d="M17 4V16L20 13.5L23 16V4" fill="#94a3b8"/>
                                            <circle cx="21" cy="22" r="4.5" fill="#02151e" stroke="#ffffff" stroke-width="1"/>
                                            <polygon points="21,18.5 22.2,21 24.8,21.4 22.8,23 23.5,25.6 21,24 18.5,25.6 19.2,23 17.2,21.4 19.8,21" fill="#f8fafc"/>
                                        </svg>
                                    </div>
                                    <h2 class="bookshelf-title" data-i18n="pg_lib_collections">Makusanyo</h2>
                                </div>

                                @if($total === 0)
                                    <div class="catalog-empty-status">
                                        <span class="status-pill"><span></span> <b data-i18n="pg_lib_pending">Inasubiri maudhui yaliyohakikiwa</b></span>
                                    </div>
                                @endif
                            </div>

                            <div class="gilded-bookshelf-grid">
                                @foreach($collections as $item)
                                    @php
                                        $meta = $narratorMeta[$item->slug] ?? null;
                                        $count = $collectionCounts[$item->id] ?? 0;
                                        $totalHadiths = number_format($count);
                                        $sample = isset($collectionPreviews[$item->slug]) ? $collectionPreviews[$item->slug]->first() : null;
                                        $badge = $meta['badge'] ?? 'SAHIH';
                                    @endphp
                                    <button type="button" class="rawdah-book-card {{ !empty($meta['cover_image']) ? 'has-custom-cover' : '' }}" wire:key="book-card-{{ $item->id }}" wire:click="chooseCollection('{{ $item->slug }}')">
                                        @if(!empty($meta['cover_image']))
                                            <div class="rawdah-book-cover-wrap" aria-hidden="true">
                                                <img src="{{ asset($meta['cover_image']) }}" alt="{{ $item->name }}" class="rawdah-book-cover-img" loading="lazy" width="95" height="148">
                                            </div>
                                        @endif

                                        <div class="rawdah-book-main">


                                            <div class="rawdah-book-body">
                                                <h3 class="rawdah-book-title">{{ $item->name }}</h3>
                                                @if($item->name_ar)
                                                    <span class="rawdah-book-ar" lang="ar" dir="rtl">{{ $item->name_ar }}</span>
                                                @endif
                                                @if(!empty($meta['author']))
                                                    <p class="rawdah-book-author">
                                                        {{ $meta['author'] }}@if(!empty($meta['desc'])) · {{ $meta['desc'] }}@endif
                                                    </p>
                                                @endif
                                            </div>

                                            <div class="rawdah-book-footer" aria-hidden="true">
                                                <span class="book-total-hadiths"><span data-i18n="pg_lib_total">Jumla ya Hadithi</span> {{ $totalHadiths }}</span>
                                                <span class="rawdah-book-action">
                                                    <span data-i18n="pg_lib_start">Anza Kusoma</span>
                                                    <span class="rawdah-arrow">→</span>
                                                </span>
                                            </div>
                                        </div>

                                        @if($sample)
                                            <span class="sr-only">{{ $sample->display_text }}</span>
                                        @endif
                                    </button>
                                @endforeach
                            </div>
                        </section>

                        {{-- 2b. Platform modules --}}
                        <section class="platform-modules" aria-labelledby="platform-modules-title">
                            <h2 id="platform-modules-title" class="platform-modules-title" data-i18n="pg_mod_title">Jukwaa la Maarifa</h2>
                            <p class="section-sub" data-i18n="pg_mod_sub">Zana za kujifunza na kutafiti</p>
                            <div class="module-grid">
                            <a class="module-card module-card-live" href="{{ route('hadith.today') }}">
                                <span class="module-icon" aria-hidden="true"><svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/></svg></span>
                                <span class="module-body">
                                    <span class="module-title" data-i18n="pg_mod_today">Hadith ya Leo</span>
                                    <span class="module-desc" data-i18n="pg_mod_today_d">Hadith moja kila siku</span>
                                </span>
                            </a>
                            <a class="module-card module-card-live" href="{{ route('learning.index') }}">
                                <span class="module-icon" aria-hidden="true"><svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10L12 5 2 10l10 5 10-5z"/><path d="M6 12v5c3 2 9 2 12 0v-5"/></svg></span>
                                <span class="module-body">
                                    <span class="module-title" data-i18n="pg_mod_learn">Learning Centre</span>
                                    <span class="module-desc" data-i18n="pg_mod_learn_d">Kozi na kuhifadhi</span>
                                </span>
                            </a>
                            <a class="module-card module-card-live" href="{{ route('research.hub') }}">
                                <span class="module-icon" aria-hidden="true"><svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="6" cy="6" r="2.5"/><circle cx="18" cy="6" r="2.5"/><circle cx="12" cy="18" r="2.5"/><path d="M8 7.5l3 8M16 7.5l-3 8M8.5 6h7"/></svg></span>
                                <span class="module-body">
                                    <span class="module-title" data-i18n="pg_mod_res">Research Intelligence</span>
                                    <span class="module-desc" data-i18n="pg_mod_res_d">Ulinganisho na Isnad</span>
                                </span>
                            </a>
                            <a class="module-card module-card-live" href="{{ route('research.hub') }}#ramani">
                                <span class="module-icon" aria-hidden="true"><svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="2.5"/><circle cx="5" cy="5" r="2"/><circle cx="19" cy="5" r="2"/><circle cx="5" cy="19" r="2"/><circle cx="19" cy="19" r="2"/><path d="M6.5 6.5l4 4M17.5 6.5l-4 4M6.5 17.5l4-4M17.5 17.5l-4-4"/></svg></span>
                                <span class="module-body">
                                    <span class="module-title" data-i18n="pg_mod_kg">Knowledge Graph</span>
                                    <span class="module-desc" data-i18n="pg_mod_kg_d">Maarifa yaliyounganishwa</span>
                                </span>
                            </a>
                            <a class="module-card module-card-live" href="{{ route('arabic.index') }}">
                                <span class="module-icon" aria-hidden="true"><svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7h9M4 12h6M4 17h12"/><path d="M17 5l3 3-3 3"/></svg></span>
                                <span class="module-body">
                                    <span class="module-title" data-i18n="pg_mod_ara">Arabic Analyzer</span>
                                    <span class="module-desc" data-i18n="pg_mod_ara_d">Lugha na mofolojia</span>
                                </span>
                            </a>
                            @if(\App\Http\Controllers\AiResearchController::enabled())
                            <a class="module-card module-card-live" href="{{ route('ai.index') }}">
                                <span class="module-icon" aria-hidden="true"><svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3l1.8 5.2L19 10l-5.2 1.8L12 17l-1.8-5.2L5 10l5.2-1.8z"/><path d="M19 17l.7 1.8L21.5 19.5l-1.8.7L19 22l-.7-1.8-1.8-.7 1.8-.7z"/></svg></span>
                                <span class="module-body">
                                    <span class="module-title" data-i18n="pg_mod_ai">AI Research</span>
                                    <span class="module-desc" data-i18n="pg_mod_ai_d">Msaidizi anayetegemea vyanzo</span>
                                </span>
                            </a>
                            @else
                            <div class="module-card module-card-soon" aria-disabled="true">
                                <span class="module-icon" aria-hidden="true"><svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3l1.8 5.2L19 10l-5.2 1.8L12 17l-1.8-5.2L5 10l5.2-1.8z"/><path d="M19 17l.7 1.8L21.5 19.5l-1.8.7L19 22l-.7-1.8-1.8-.7 1.8-.7z"/></svg></span>
                                <span class="module-body">
                                    <span class="module-title" data-i18n="pg_mod_ai">AI Research</span>
                                    <span class="module-desc" data-i18n="pg_mod_ai_d">Msaidizi anayetegemea vyanzo</span>
                                    <span class="module-badge" data-i18n="pg_mod_soon">Inakuja Hivi Karibuni</span>
                                </span>
                            </div>
                            @endif
                        </div>
                        </section>



                    </div>
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
                            <div class="inside-book-banner ib-card">
                                <div class="ib-head">
                                    @if(!empty($activeMeta['cover_image']))
                                        <div class="inside-book-cover-thumb" aria-hidden="true">
                                            <img src="{{ asset($activeMeta['cover_image']) }}" alt="{{ $activeCol->name }}" class="inside-book-thumb-img" loading="eager" width="90" height="140">
                                        </div>
                                    @endif
                                    <div class="ib-titles">
                                        <h2 class="inside-book-heading">{{ $activeCol->name }}</h2>
                                        @if($activeCol->name_ar)
                                            <p class="inside-book-ar" lang="ar" dir="rtl">{{ $activeCol->name_ar }}</p>
                                        @endif
                                    </div>
                                </div>
                                @if($activeMeta)
                                    <dl class="ib-facts">
                                        <div class="ib-fact ib-fact--wide">
                                            <dt data-i18n="pg_lib_author">Mkusanyaji:</dt>
                                            <dd data-i18n="pg_bk_a_{{ $collection }}">{{ $activeMeta['author'] }}</dd>
                                        </div>
                                        @if(!empty($activeMeta['years_ce']))
                                        <div class="ib-fact">
                                            <dt data-i18n="pg_lib_f_life">Maisha</dt>
                                            <dd>{{ $activeMeta['years_ce'] }} <span class="ib-unit">CE</span><br><span class="ib-sub">{{ $activeMeta['years'] }}</span></dd>
                                        </div>
                                        @endif
                                        <div class="ib-fact">
                                            <dt data-i18n="pg_lib_f_inapp">Zilizomo hapa</dt>
                                            <dd>{{ number_format($collectionCounts[$activeCol->id] ?? 0) }}</dd>
                                        </div>
                                        <div class="ib-fact {{ !empty($activeMeta['years_ce']) ? 'ib-fact--wide' : '' }}">
                                            <dt data-i18n="pg_lib_f_official">Jumla rasmi</dt>
                                            <dd>{{ $activeMeta['total_hadiths'] }}</dd>
                                        </div>
                                    </dl>
                                    <p class="inside-book-desc" data-i18n="pg_bk_d_{{ $collection }}">{{ $activeMeta['desc'] }}</p>
                                @endif
                            </div>
                        @endif

                    @endif

                    @if($search !== '')
                        <div class="search-active-banner">
                            <span class="search-active-text">
                                <span data-i18n="pg_lib_results_for">Matokeo ya utafutaji wa:</span> <strong>“{{ $search }}”</strong> ({{ $hadiths->total() }} <span data-i18n="pg_lib_found">zilipatikana</span>)
                            </span>
                            <button type="button" class="text-button" wire:click="$set('search', '')" data-i18n="pg_lib_clear_search">Ondoa utafutaji ×</button>
                        </div>
                    @endif

                    @forelse($hadiths as $hadith)
                        <article class="hadith-card" wire:key="hadith-{{ $hadith->id }}">
                            <div class="card-top">
                                <span class="badge badge-{{ $hadith->chapter->book->collection->slug }}">
                                    {{ $hadith->chapter->book->collection->name }}
                                </span>
                                <span class="hadith-num"><span data-i18n="pg_lib_no">Na.</span> {{ $hadith->number }}</span>
                            </div>
                            <h3>{{ $hadith->chapter->title_sw }}</h3>
                            
                            <div class="hadith-texts">
                                <div data-reading="ar" class="arabic-box">
                                    <p class="arabic excerpt" lang="ar" dir="rtl">{{ $hadith->arabic }}</p>
                                </div>
                                <p data-reading="sw" lang="{{ $hadith->has_swahili ? 'sw' : 'en' }}" class="translation swahili-text excerpt">
                                    @unless($hadith->has_swahili)<span class="en-only-tag" data-i18n="pg_lib_en_only">English</span> @endunless{!! $highlight($hadith->display_text, $search) !!}
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
                                <span class="status-pill"><span></span> <b data-i18n="pg_lib_pending">Inasubiri maudhui yaliyohakikiwa</b></span>
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
                            <span class="page-indicator page-jump" x-data>
                                <label for="page-jump-input" data-i18n="pg_lib_page">Ukurasa</label>
                                <input id="page-jump-input" type="number" inputmode="numeric" min="1" max="{{ $hadiths->lastPage() }}" value="{{ $hadiths->currentPage() }}"
                                       x-on:change="$wire.gotoPage(Math.min(Math.max(parseInt($event.target.value) || 1, 1), {{ $hadiths->lastPage() }}))"
                                       x-on:keydown.enter.prevent="$event.target.blur()">
                                <span>/ {{ $hadiths->lastPage() }}</span>
                            </span>
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
            function initRawdahHero() {
                const copyBtn = document.getElementById('rawdah-copy-btn');

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
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initRawdahHero);
            } else {
                initRawdahHero();
            }
            document.addEventListener('livewire:navigated', initRawdahHero);
        })();
    </script>
</div>
