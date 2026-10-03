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

        <div class="reader-actions no-print">
            <button type="button" id="share-dua" class="text-button action-pill-btn" data-share-text="{{ $dua->title_sw.': '.$dua->swahili }}" data-share-url="{{ url()->current() }}">
                <span class="btn-icon" aria-hidden="true">⧉</span>
                <span class="btn-text">Nakili / Shiriki</span>
            </button>
            <a class="text-button action-pill-btn" href="{{ route('tasbih') }}">
                <span>Fungua Digital Tasbih</span>
            </a>
            <a class="text-button action-pill-btn" href="{{ route('duaa.index') }}">
                <span>Dua Zote</span>
                <span aria-hidden="true">→</span>
            </a>
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
                    // Click again after completed allows resetting or continuing
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
                        } catch (_) {}
                    } else if (navigator.clipboard) {
                        await navigator.clipboard.writeText(`${text}\n\n${url}`);
                        const btnText = shareBtn.querySelector('.btn-text');
                        if (btnText) {
                            const original = btnText.textContent;
                            btnText.textContent = 'Imenakiliwa!';
                            setTimeout(() => { btnText.textContent = original; }, 2000);
                        }
                    }
                });
            }
        });
    </script>
</x-layouts.app>
