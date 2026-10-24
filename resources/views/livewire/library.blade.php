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
                    <div class="rawdah-layout-container">
                        {{-- 1. Hero Mehrab Spotlight Card --}}
                        @if($todayHadith)
                            <section class="rawdah-mehrab-hero" aria-label="Hadithi Teule ya Leo">
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
                                        <span>Hadithi Teule ya Leo</span>
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

                                {{-- Action Bar with Audio & Quick Links --}}
                                <div class="rawdah-hero-footer">
                                    @php
                                        $hasStudio = !empty($todayHadith->getStudioAudioUrl());
                                        $arAudioUrl = route('hadith.audio', ['hadith' => $todayHadith, 'lang' => 'ar']);
                                        $swAudioUrl = route('hadith.audio', ['hadith' => $todayHadith, 'lang' => 'sw']);
                                    @endphp
                                    <div class="rawdah-audio-pill" id="rawdah-audio-pill"
                                         data-ar-audio="{{ $arAudioUrl }}"
                                         data-sw-audio="{{ $swAudioUrl }}"
                                         data-arabic="{{ e($todayHadith->arabic) }}"
                                         data-swahili="{{ e($todayHadith->swahili) }}"
                                         data-has-studio="{{ $hasStudio ? '1' : '0' }}">
                                        <button type="button" class="rawdah-play-btn" id="rawdah-audio-toggle" aria-label="Sikiliza Kisomo cha Hadithi" title="Sikiliza Kisomo cha Sauti">
                                            <svg class="icon-play" viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
                                                <path d="M8 5v14l11-7z"/>
                                            </svg>
                                            <svg class="icon-pause" viewBox="0 0 24 24" width="16" height="16" fill="currentColor" style="display: none;">
                                                <path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/>
                                            </svg>
                                        </button>
                                        <div class="rawdah-audio-meta">
                                            <span class="rawdah-audio-title" id="rawdah-audio-title">Kisomo cha Kiarabu</span>
                                            <span class="rawdah-audio-sub" id="rawdah-audio-sub">
                                                {{ $hasStudio ? 'Kisomo Halisi cha Sheikh (MP3)' : 'Kiarabu Fasaha (MP3)' }}
                                            </span>
                                        </div>
                                        <div class="rawdah-audio-lang-switch" role="group" aria-label="Chagua Lugha ya Sauti">
                                            <button type="button" class="lang-switch-btn active" id="btn-lang-ar" data-lang="ar" title="Sikiliza Kiarabu">AR</button>
                                            <button type="button" class="lang-switch-btn" id="btn-lang-sw" data-lang="sw" title="Sikiliza Kiswahili">SW</button>
                                        </div>
                                        <audio id="rawdah-html5-audio" preload="none"></audio>
                                    </div>

                                    <div class="rawdah-hero-buttons">
                                        <a href="{{ route('hadith.show', $todayHadith) }}" class="rawdah-btn-primary">
                                            <span>Soma Hadithi Kamili</span>
                                            <span aria-hidden="true">→</span>
                                        </a>

                                        <button type="button" class="rawdah-btn-icon" id="rawdah-copy-btn" title="Nakili hadithi" data-copy="{{ $todayHadith->swahili }} — {{ $todayHadith->arabic }} ({{ $todayHadith->reference }})">
                                            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                                                <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                                            </svg>
                                        </button>

                                        @php
                                            $shareText = urlencode("Hadithi ya Leo kutoka Bayt Al-Hadith:\n\n\"" . Str::limit($todayHadith->swahili, 220) . "\"\n\nSoma zaidi: " . route('hadith.show', $todayHadith));
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

                        {{-- 2. Thematic Topics Quick Row --}}
                        <div class="rawdah-topics-row" role="region" aria-label="Gundua kwa Mada">
                            <span class="rawdah-topics-label">Gundua kwa Mada:</span>
                            @php
                                $topicsSlice = array_slice($topics, 0, 6, true);
                            @endphp
                            @foreach($topicsSlice as $t)
                                <a href="{{ route('topics.show', $t['slug']) }}" class="rawdah-topic-pill">
                                    <span style="color: {{ $t['color'] ?? 'var(--gold)' }};">✦</span>
                                    <span>{{ $t['title_sw'] }}</span>
                                </a>
                            @endforeach
                            <a href="{{ route('topics.index') }}" class="rawdah-topic-all-link">
                                <span>Tazama Mada Zote 12</span>
                                <span aria-hidden="true">→</span>
                            </a>
                        </div>

                        {{-- 3. The Gilded Bookshelf: Collections --}}
                        <section class="gilded-bookshelf-section" aria-label="Collections za Vitabu">
                            <div class="bookshelf-header">
                                <div class="bookshelf-title-wrap">
                                    <div class="hadith-header-art" aria-hidden="true">
                                        <svg viewBox="0 0 36 36" width="32" height="32" fill="none">
                                            <rect x="7" y="4" width="23" height="28" rx="3.5" fill="#5c3818" stroke="#D4AF37" stroke-width="1.4"/>
                                            <path d="M11 4V32" stroke="#854d1f" stroke-width="2"/>
                                            <path d="M12 4V32" stroke="#D4AF37" stroke-width="0.8"/>
                                            <path d="M17 4V16L20 13.5L23 16V4" fill="#D4AF37"/>
                                            <circle cx="21" cy="22" r="4.5" fill="#3d220a" stroke="#E5C158" stroke-width="1"/>
                                            <polygon points="21,18.5 22.2,21 24.8,21.4 22.8,23 23.5,25.6 21,24 18.5,25.6 19.2,23 17.2,21.4 19.8,21" fill="#E5C158"/>
                                        </svg>
                                    </div>
                                    <h2 class="bookshelf-title">Collections</h2>
                                </div>
                                <div class="bookshelf-badge-pill">
                                    <span>Hadithi {{ number_format($total) }} Zilizohakikiwa</span>
                                </div>
                                @if($total === 0)
                                    <div class="catalog-empty-status">
                                        <span class="status-pill"><span></span> Inasubiri maudhui yaliyohakikiwa</span>
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
                                    <button type="button" class="gilded-book-card" wire:key="book-card-{{ $item->id }}" wire:click="chooseCollection('{{ $item->slug }}')">
                                        <div class="book-gilded-spine" aria-hidden="true"></div>

                                        <div class="gilded-book-top">
                                            <span class="book-category-tag">{{ $badge }}</span>
                                            <span class="book-total-hadiths">Total Hadiths {{ $totalHadiths }}</span>
                                        </div>

                                        <div class="gilded-book-middle">
                                            <h3 class="gilded-book-title">{{ $item->name }}</h3>
                                            @if($item->name_ar)
                                                <span class="gilded-book-ar" lang="ar" dir="rtl">{{ $item->name_ar }}</span>
                                            @endif
                                            @if(!empty($meta['author']))
                                                <p class="gilded-book-author">
                                                    {{ $meta['author'] }}@if(!empty($meta['desc'])) · {{ $meta['desc'] }}@endif
                                                </p>
                                            @endif
                                        </div>

                                        <div class="gilded-book-bottom" aria-hidden="true">
                                            <span>Anza Kusoma</span>
                                            <span class="book-action-arrow">→</span>
                                        </div>

                                        @if($sample)
                                            <span class="sr-only">{{ $sample->swahili }}</span>
                                        @endif
                                    </button>
                                @endforeach
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
            function initRawdahHero() {
                const playBtn = document.getElementById('rawdah-audio-toggle');
                const audioPill = document.getElementById('rawdah-audio-pill');
                const copyBtn = document.getElementById('rawdah-copy-btn');
                const htmlAudio = document.getElementById('rawdah-html5-audio');
                const btnAr = document.getElementById('btn-lang-ar');
                const btnSw = document.getElementById('btn-lang-sw');
                const titleEl = document.getElementById('rawdah-audio-title');
                const subEl = document.getElementById('rawdah-audio-sub');
                const badgeEl = document.getElementById('rawdah-source-badge');

                if (playBtn && audioPill && !playBtn.dataset.bound) {
                    playBtn.dataset.bound = '1';
                    let currentLang = 'ar';
                    let isPlaying = false;
                    const hasStudio = audioPill.dataset.hasStudio === '1';

                    function updateLabels() {
                        if (currentLang === 'ar') {
                            titleEl.textContent = 'Kisomo cha Kiarabu';
                            subEl.textContent = hasStudio ? 'Kisomo Halisi cha Sheikh (MP3)' : 'Kiarabu Fasaha (MP3)';
                            btnAr.classList.add('active');
                            btnSw.classList.remove('active');
                        } else {
                            titleEl.textContent = 'Kisomo cha Kiswahili';
                            subEl.textContent = 'Tafsiri ya Kiswahili Sanifu (MP3)';
                            btnSw.classList.add('active');
                            btnAr.classList.remove('active');
                        }
                    }

                    function setPlayState(playing) {
                        isPlaying = playing;
                        const p = playBtn.querySelector('.icon-play');
                        const pa = playBtn.querySelector('.icon-pause');
                        if (p) p.style.display = playing ? 'none' : 'block';
                        if (pa) pa.style.display = playing ? 'block' : 'none';
                        audioPill.classList.toggle('is-playing', playing);
                    }

                    function playCurrentAudio() {
                        const targetUrl = (currentLang === 'ar') ? audioPill.dataset.arAudio : audioPill.dataset.swAudio;
                        if (!htmlAudio.src || !htmlAudio.src.includes(targetUrl)) {
                            htmlAudio.src = targetUrl;
                        }
                        const playPromise = htmlAudio.play();
                        if (playPromise !== undefined) {
                            playPromise.then(function() {
                                setPlayState(true);
                            }).catch(function() {
                                // Fallback to Web Speech API if HTML5 audio fails
                                fallbackSpeech();
                            });
                        }
                    }

                    function pauseAudio() {
                        htmlAudio.pause();
                        if ('speechSynthesis' in window) window.speechSynthesis.cancel();
                        setPlayState(false);
                    }

                    function fallbackSpeech() {
                        if (!('speechSynthesis' in window)) {
                            alert('Sauti ya mtandaoni haijapatikana na kifaa hakiauni usomaji wa ndani.');
                            setPlayState(false);
                            return;
                        }
                        window.speechSynthesis.cancel();
                        const text = (currentLang === 'ar') ? audioPill.dataset.arabic : audioPill.dataset.swahili;
                        const utterance = new SpeechSynthesisUtterance(text);
                        utterance.lang = (currentLang === 'ar') ? 'ar-SA' : 'sw-TZ';
                        utterance.rate = 0.85;

                        utterance.onstart = function() { setPlayState(true); };
                        utterance.onend = utterance.onerror = function() { setPlayState(false); };
                        window.speechSynthesis.speak(utterance);
                    }

                    htmlAudio.addEventListener('ended', function() { setPlayState(false); });
                    htmlAudio.addEventListener('pause', function() { setPlayState(false); });

                    playBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        if (isPlaying) {
                            pauseAudio();
                        } else {
                            playCurrentAudio();
                        }
                    });

                    if (btnAr && btnSw) {
                        btnAr.addEventListener('click', function(e) {
                            e.preventDefault();
                            if (currentLang === 'ar') return;
                            currentLang = 'ar';
                            updateLabels();
                            if (isPlaying) {
                                pauseAudio();
                                playCurrentAudio();
                            }
                        });

                        btnSw.addEventListener('click', function(e) {
                            e.preventDefault();
                            if (currentLang === 'sw') return;
                            currentLang = 'sw';
                            updateLabels();
                            if (isPlaying) {
                                pauseAudio();
                                playCurrentAudio();
                            }
                        });
                    }
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
