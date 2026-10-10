<x-layouts.app :title="$dua->title_sw.' · Dua & Adhkar'">
    <div class="reader-shell dua-detail-shell">
        <a class="back-link" href="{{ route('duaa.index', ['kundi' => $dua->category->slug]) }}">
            <span class="back-arrow" aria-hidden="true">←</span>
            <span>Rudi kwenye {{ $dua->category->name_sw }}</span>
        </a>

        <div class="reader-heading dua-detail-heading">
            <span class="eyebrow"><span class="eyebrow-dot"></span> {{ $dua->category->name_sw }} · HISN AL-MUSLIM</span>
            <h1>{{ $dua->title_sw }}</h1>
            @if($dua->title_ar)
                <p class="reader-subtitle font-arabic" dir="rtl">{{ $dua->title_ar }}</p>
            @endif
        </div>

        @php
            $shareArabic = trim((string) $dua->arabic);
            $shareArabicShort = \Illuminate\Support\Str::limit($shareArabic, 320);
            $shareSwahiliShort = \Illuminate\Support\Str::limit($dua->swahili, 420);
            $shareTitle = $dua->title_sw;
            $shareBadge = $dua->category->name_sw;
            $shareRef = "Hisn al-Muslim (" . ($dua->reference ?? 'Rejea Rasmi') . ") · Lengo: Mara " . $dua->target_count;
            $shareFormatted = "Dua: " . $dua->title_sw . ($dua->title_ar ? " (" . $dua->title_ar . ")" : "") . "\n\n"
                . ($shareArabicShort ? $shareArabicShort . "\n\n" : "")
                . "“" . $shareSwahiliShort . "”\n\n"
                . "Rejea: " . $shareRef;
        @endphp

        <div class="reader-actions no-print">
            <button type="button" id="bookmark-dua-btn" class="text-button action-pill-btn" data-dua-id="{{ $dua->id }}" data-dua-title="{{ $dua->title_sw }}" data-dua-cat="{{ $dua->category->name_sw }}" data-dua-excerpt="{{ \Illuminate\Support\Str::limit($dua->swahili, 90) }}" aria-pressed="false">
                <span class="btn-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                    </svg>
                </span>
                <span class="btn-text">Hifadhi</span>
            </button>

            <button type="button" id="share-dua" class="text-button action-pill-btn share-trigger-btn"
                data-share-title="{{ $shareTitle }}"
                data-share-badge="{{ $shareBadge }}"
                data-share-ar="{{ $shareArabicShort }}"
                data-share-sw="{{ $shareSwahiliShort }}"
                data-share-ref="{{ $shareRef }}"
                data-share-text="{{ $shareFormatted }}"
                data-share-url="{{ url()->current() }}">
                <span class="btn-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"></path>
                        <polyline points="16 6 12 2 8 6"></polyline>
                        <line x1="12" y1="2" x2="12" y2="15"></line>
                    </svg>
                </span>
                <span class="btn-text">Shiriki</span>
            </button>

            {{-- Kidhibiti cha Ukubwa wa Maandishi (Font Size Scaler) --}}
            <div class="font-scaler-widget" aria-label="Rekebisha ukubwa wa maandishi">
                <button type="button" id="font-decrease" class="scaler-btn" title="Punguza ukubwa wa maandishi" aria-label="Punguza ukubwa">A-</button>
                <span id="font-scale-display" class="scaler-label" title="Ukubwa wa sasa">100%</span>
                <button type="button" id="font-increase" class="scaler-btn" title="Ongeza ukubwa wa maandishi" aria-label="Ongeza ukubwa">A+</button>
            </div>
        </div>

        <article class="reader-card dua-detail-card">
            {{-- Sehemu ya Juu ya Kadi --}}
            <div class="dua-detail-header">
                <span class="dua-category-badge">{{ $dua->category->name_sw }}</span>
                <span class="dua-repeat-badge">Soma mara {{ $dua->target_count }}</span>
            </div>

            {{-- 1. Matini ya Kiarabu --}}
            <section class="reading-section" data-reading="ar" aria-labelledby="label-arabic">
                <div class="section-label-row">
                    <span id="label-arabic" class="section-label">MAANDISHI YA KIARABU</span>
                    <span class="section-script-badge">العربية بالتشكيل</span>
                </div>
                <div class="arabic-box dua-arabic-box-detail">
                    <p class="arabic-text font-arabic" dir="rtl">{{ $dua->arabic }}</p>
                </div>
            </section>

            {{-- 2. Matamshi ya Kilatini (Transliteration) --}}
            @if($dua->transliteration)
                <section class="reading-section" data-reading="transliteration" aria-labelledby="label-transliteration">
                    <div class="section-label-row">
                        <span id="label-transliteration" class="section-label">MATAMSHI (NAMNA YA KUSOMA)</span>
                    </div>
                    <div class="transliteration-box">
                        <p class="transliteration-text">"{{ $dua->transliteration }}"</p>
                    </div>
                </section>
            @endif

            {{-- 3. Tafsiri ya Kiswahili --}}
            <section class="reading-section" data-reading="sw" aria-labelledby="label-swahili">
                <div class="section-label-row">
                    <span id="label-swahili" class="section-label">TAFSIRI YA KISWAHILI</span>
                </div>
                <div class="swahili-box">
                    <p class="swahili-text">{{ $dua->swahili }}</p>
                </div>
            </section>

            {{-- 4. Tafsiri ya Kiingereza (kama ipo) --}}
            @if($dua->english)
                <section class="reading-section" data-reading="en" aria-labelledby="label-english">
                    <div class="section-label-row">
                        <span id="label-english" class="section-label">ENGLISH TRANSLATION</span>
                    </div>
                    <div class="english-box">
                        <p class="english-text">{{ $dua->english }}</p>
                    </div>
                </section>
            @endif

            {{-- 5. Kaunta ya Kuhesabu Dua Hii (Interactive Repeat Counter) --}}
            <section class="dua-counter-widget" id="dua-counter-widget" data-target="{{ $dua->target_count }}">
                <div class="counter-widget-header">
                    <span class="counter-widget-label">KAUNTA YA KUSOMA DUA HII</span>
                    <span class="counter-target-text">Lengo: Mara {{ $dua->target_count }}</span>
                </div>
                
                <div class="counter-widget-body">
                    <button type="button" id="dua-count-btn" class="dua-count-btn" aria-label="Gusa ili kuhesabu">
                        <span class="dua-count-number" id="dua-count-number">0</span>
                        <span class="dua-count-slash">/</span>
                        <span class="dua-count-total">{{ $dua->target_count }}</span>
                        <span class="dua-count-hint">Gusa ili kuhesabu</span>
                    </button>
                    
                    <div class="counter-widget-controls">
                        <button type="button" id="dua-count-reset" class="counter-reset-btn" title="Anza upya">
                            <span aria-hidden="true">↺</span> Anza upya
                        </button>
                    </div>
                </div>

                <div class="counter-completed-msg" id="counter-completed-msg" hidden>
                    MashaAllah! Umekamilisha idadi ya kusoma dua hii. Mwenyezi Mungu akukubalie.
                </div>
            </section>

            {{-- 6. Fadhila za Dua --}}
            @if($dua->virtue_sw)
                <div class="dua-virtue-callout">
                    <div class="virtue-header">
                        <strong>Fadhila za Dua Hii:</strong>
                    </div>
                    <p class="virtue-text">{{ $dua->virtue_sw }}</p>
                </div>
            @endif

            {{-- 7. Rejea na Chanzo --}}
            @if($dua->reference)
                <div class="source-box dua-ref-box">
                    <div class="source-header">
                        <h2 class="source-title">Rejea na Chanzo</h2>
                    </div>
                    <div class="source-item">
                        <dt>Kitabu / Chanzo:</dt>
                        <dd>{{ $dua->reference }}</dd>
                    </div>
                </div>
            @endif
        </article>

        {{-- Dua Zinazofanana Katika Kundi Hili --}}
        @if($related->isNotEmpty())
            <section class="related-section dua-related-section">
                <div class="related-header">
                    <h2>Dua Nyingine Katika Kundi Hili</h2>
                </div>
                <div class="related-grid">
                    @foreach($related as $rel)
                        <a href="{{ route('duaa.show', $rel) }}" class="related-card">
                            <div class="related-card-meta">
                                <span class="related-col">{{ $dua->category->name_sw }}</span>
                                <span class="related-no">x{{ $rel->target_count }}</span>
                            </div>
                            <h3 class="related-title">{{ $rel->title_sw }}</h3>
                            <p class="related-excerpt">{{ \Illuminate\Support\Str::limit($rel->swahili, 100) }}</p>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif
        {{-- Urambazaji wa Chini (Next/Prev Navigation Cards) --}}
        <nav class="reader-bottom-nav dua-bottom-nav" aria-label="Urambazaji wa Dua">
            @if($prev)
                <a href="{{ route('duaa.show', $prev) }}" class="reader-nav-card nav-prev">
                    <span class="nav-card-icon" aria-hidden="true">←</span>
                    <div class="nav-card-content">
                        <span class="nav-card-hint nav-dir-label">DUA ILIYOTANGULIA</span>
                        <strong class="nav-card-title nav-surah-name">{{ $prev->title_sw }}</strong>
                        <span class="nav-card-excerpt">{{ \Illuminate\Support\Str::limit($prev->swahili, 60) }}</span>
                    </div>
                </a>
            @else
                <div class="reader-nav-card nav-disabled">
                    <div class="nav-card-content">
                        <span class="nav-card-hint nav-dir-label">MWANZO WA KUNDI</span>
                        <strong class="nav-card-title nav-surah-name">Dua ya Kwanza</strong>
                    </div>
                </div>
            @endif

            <a href="{{ route('duaa.index', ['kundi' => $dua->category->slug]) }}" class="reader-nav-card nav-center" title="Rudi kwenye kundi hili">
                <span class="nav-card-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 2a10 10 0 0 1 10 10c0 5.5-4.5 10-10 10S2 17.5 2 12A10 10 0 0 1 12 2z"></path>
                        <path d="m9 12 2 2 4-4"></path>
                    </svg>
                </span>
                <div class="nav-card-content">
                    <span class="nav-card-hint nav-dir-label">KUNDI LA DUA</span>
                    <strong class="nav-card-title nav-surah-name">{{ \Illuminate\Support\Str::limit($dua->category->name_sw, 24) }}</strong>
                </div>
            </a>

            @if($next)
                <a href="{{ route('duaa.show', $next) }}" class="reader-nav-card nav-next">
                    <div class="nav-card-content">
                        <span class="nav-card-hint nav-dir-label">DUA INAYOFUATA</span>
                        <strong class="nav-card-title nav-surah-name">{{ $next->title_sw }}</strong>
                        <span class="nav-card-excerpt">{{ \Illuminate\Support\Str::limit($next->swahili, 60) }}</span>
                    </div>
                    <span class="nav-card-icon" aria-hidden="true">→</span>
                </a>
            @else
                <div class="reader-nav-card nav-disabled">
                    <div class="nav-card-content">
                        <span class="nav-card-hint nav-dir-label">MWISHO WA KUNDI</span>
                        <strong class="nav-card-title nav-surah-name">Dua ya Mwisho</strong>
                    </div>
                </div>
            @endif
        </nav>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const widget = document.getElementById('dua-counter-widget');
            if (!widget) return;
            const target = parseInt(widget.dataset.target, 10) || 1;
            const countBtn = document.getElementById('dua-count-btn');
            const numEl = document.getElementById('dua-count-number');
            const resetBtn = document.getElementById('dua-count-reset');
            const compMsg = document.getElementById('counter-completed-msg');
            const shareBtn = document.getElementById('share-dua');
            const bookmarkBtn = document.getElementById('bookmark-dua-btn');

            let currentCount = 0;

            function updateUI() {
                numEl.textContent = currentCount;
                if (currentCount >= target) {
                    countBtn.classList.add('is-completed');
                    if (compMsg) compMsg.hidden = false;
                } else {
                    countBtn.classList.remove('is-completed');
                    if (compMsg) compMsg.hidden = true;
                }
            }

            countBtn.addEventListener('click', () => {
                if (currentCount < target) {
                    currentCount++;
                    if ('vibrate' in navigator) {
                        navigator.vibrate(15);
                    }
                    if (currentCount === target && 'vibrate' in navigator) {
                        navigator.vibrate([40, 60, 40]);
                    }
                    updateUI();
                } else {
                    currentCount = 0;
                    updateUI();
                }
            });

            resetBtn.addEventListener('click', () => {
                currentCount = 0;
                updateUI();
            });

            if (shareBtn) {
                shareBtn.addEventListener('click', async () => {
                    const text = shareBtn.getAttribute('data-share-text');
                    const url = shareBtn.getAttribute('data-share-url');
                    if (navigator.share) {
                        try {
                            await navigator.share({ title: document.title, text: text, url: url });
                            return;
                        } catch (_) {}
                    }
                    if (navigator.clipboard) {
                        await navigator.clipboard.writeText(`${text}\n\n📲 Soma zaidi: ${url}`);
                        const btnText = shareBtn.querySelector('.btn-text');
                        if (btnText) {
                            const original = btnText.textContent;
                            btnText.textContent = 'Imenakiliwa!';
                            setTimeout(() => { btnText.textContent = original; }, 2000);
                        }
                    }
                });
            }

            if (bookmarkBtn) {
                const duaId = String(bookmarkBtn.dataset.duaId);
                const duaTitle = bookmarkBtn.dataset.duaTitle;
                const duaCat = bookmarkBtn.dataset.duaCat;
                const duaExcerpt = bookmarkBtn.dataset.duaExcerpt;
                const url = window.location.href;

                const getDuaBookmarks = () => {
                    try { return JSON.parse(localStorage.getItem('dua-bookmarks')) || []; } catch (_) { return []; }
                };

                const setDuaBookmarks = items => {
                    try { localStorage.setItem('dua-bookmarks', JSON.stringify(items)); } catch (_) {}
                };

                const isBookmarked = () => getDuaBookmarks().some(item => String(item.id) === duaId);

                const updateBookmarkUI = saved => {
                    bookmarkBtn.setAttribute('aria-pressed', saved ? 'true' : 'false');
                    const textSpan = bookmarkBtn.querySelector('.btn-text');
                    const iconSpan = bookmarkBtn.querySelector('.btn-icon');
                    if (textSpan) textSpan.textContent = saved ? 'Imehifadhiwa' : 'Hifadhi';
                    if (iconSpan) {
                        iconSpan.innerHTML = saved
                            ? `<svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>`
                            : `<svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>`;
                    }
                };

                updateBookmarkUI(isBookmarked());

                bookmarkBtn.addEventListener('click', () => {
                    let list = getDuaBookmarks();
                    if (isBookmarked()) {
                        list = list.filter(item => String(item.id) !== duaId);
                        setDuaBookmarks(list);
                        updateBookmarkUI(false);
                    } else {
                        list.unshift({ id: duaId, title: duaTitle, category: duaCat, excerpt: duaExcerpt, url: url, saved_at: new Date().toISOString() });
                        setDuaBookmarks(list);
                        updateBookmarkUI(true);
                    }
                });
            }
        });
    </script>
</x-layouts.app>
