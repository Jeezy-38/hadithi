<x-layouts.app :title="$hadith->chapter->book->collection->name.' · '.$hadith->number">
    <div class="reader-shell hadith-reader-shell dua-detail-shell hadith-detail-shell">
        <a class="back-link" href="{{ route('library', ['collection' => $hadith->chapter->book->collection->slug, 'book' => $hadith->chapter->book_id, 'chapter' => $hadith->chapter_id]) }}">
            <span class="back-arrow" aria-hidden="true">←</span>
            <span>Rudi kwenye {{ $hadith->chapter->title_sw }}</span>
        </a>

        <div class="reader-heading dua-detail-heading">
            <span class="eyebrow"><span class="eyebrow-dot"></span> {{ $hadith->chapter->book->collection->name }} · HADITHI NA. {{ $hadith->number }}</span>
            <h1>Hadith {{ $hadith->number }}</h1>
            @if($hadith->title)
                <h2 class="reader-title">{{ $hadith->title }}</h2>
            @endif
            <p class="reader-subtitle">{{ $hadith->chapter->book->title_sw }} · {{ $hadith->chapter->title_sw }}</p>
        </div>

        @php
            $shareArabic = trim((string) $hadith->arabic);
            $shareArabicShort = \Illuminate\Support\Str::limit($shareArabic, 320);
            $shareSwahiliShort = \Illuminate\Support\Str::limit($hadith->swahili, 420);
            $shareTitle = "Hadith " . $hadith->number;
            $shareBadge = $hadith->chapter->book->collection->name;
            $shareRef = $hadith->chapter->book->collection->name . " Na. " . $hadith->number . " (" . $hadith->chapter->book->title_sw . " · " . $hadith->chapter->title_sw . ")";
            $shareFormatted = "Hadithi: " . $shareBadge . " Na. " . $hadith->number . "\n\n"
                . ($shareArabicShort ? $shareArabicShort . "\n\n" : "")
                . "“" . $shareSwahiliShort . "”\n\n"
                . "Rejea: " . $shareRef;
        @endphp

        <div class="reader-actions no-print">
            <button type="button" id="bookmark-toggle" class="text-button action-pill-btn" data-hadith-id="{{ $hadith->id }}" aria-pressed="false">
                <span class="btn-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                    </svg>
                </span>
                <span class="btn-text">Hifadhi</span>
            </button>

            <button type="button" id="share-hadith" class="text-button action-pill-btn share-trigger-btn"
                data-share-title="{{ $shareTitle }}"
                data-share-badge="{{ $shareBadge }}"
                data-share-ar="{{ $shareArabicShort }}"
                data-share-sw="{{ $shareSwahiliShort }}"
                data-share-ref="{{ $shareRef }}"
                data-share-text="{{ $shareFormatted }}"
                data-share-url="{{ route('hadith.show', $hadith) }}">
                <span class="btn-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"></path>
                        <polyline points="16 6 12 2 8 6"></polyline>
                        <line x1="12" y1="2" x2="12" y2="15"></line>
                    </svg>
                </span>
                <span class="btn-text">Shiriki</span>
            </button>

            <button type="button" id="audio-play" class="text-button action-pill-btn speech-client-btn" title="Sikiliza kwa sauti ya kifaa">
                <span class="btn-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon>
                        <path d="M15.54 8.46a5 5 0 0 1 0 7.07"></path>
                        <path d="M19.07 4.93a10 10 0 0 1 0 14.14"></path>
                    </svg>
                </span>
                <span class="btn-text">Soma kwa sauti</span>
            </button>

            <input type="hidden" id="reading-language" value="both">
        </div>

        <article class="reader-card dua-detail-card hadith-detail-card">
            {{-- Sehemu ya Juu ya Kadi --}}
            <div class="dua-detail-header">
                <span class="dua-category-badge">{{ $hadith->chapter->book->collection->name }}</span>
                <span class="dua-repeat-badge">{{ $hadith->grade ?? 'Sahihi' }}</span>
            </div>

            {{-- 1. Matini ya Kiarabu --}}
            <section class="reading-section" data-reading="ar" aria-labelledby="label-arabic">
                <div class="section-label-row">
                    <span id="label-arabic" class="section-label">MAANDISHI YA KIARABU · {{ $hadith->source_name }}</span>
                    <span class="section-script-badge">العربية بالتشكيل</span>
                </div>
                <div class="arabic-box dua-arabic-box-detail">
                    <p id="text-ar" class="arabic arabic-text font-arabic" lang="ar" dir="rtl">{{ $hadith->arabic }}</p>
                </div>
            </section>

            {{-- 2. Tafsiri ya Kiswahili --}}
            <section class="reading-section" data-reading="sw" aria-labelledby="label-swahili">
                <div class="section-label-row">
                    <span id="label-swahili" class="section-label">TAFSIRI YA KISWAHILI</span>
                </div>
                <div class="swahili-box">
                    <p id="text-sw" class="swahili-text" lang="sw">{{ $hadith->swahili }}</p>
                </div>
            </section>

            {{-- 3. Tafsiri ya Kiingereza (kama ipo) --}}
            @if($hadith->english)
                <section class="reading-section" data-reading="en" aria-labelledby="label-english">
                    <div class="section-label-row">
                        <span id="label-english" class="section-label">ENGLISH TRANSLATION</span>
                    </div>
                    <div class="english-box">
                        <p id="text-en" class="english-text" lang="en">{{ $hadith->english }}</p>
                    </div>
                </section>
            @endif

            {{-- 4. Sharh na Ufafanuzi wa Hadithi (kama upo) --}}
            @if($hadith->explanation)
                <div class="dua-virtue-callout hadith-explanation-callout">
                    <div class="virtue-header">
                        <strong>Sharh na Ufafanuzi wa Hadithi:</strong>
                    </div>
                    <div class="virtue-text">
                        {!! nl2br(e($hadith->explanation)) !!}
                    </div>
                </div>
            @endif

            {{-- 5. Mafundisho na Faida za Hadithi (kama zipo) --}}
            @if(!empty($hadith->hints) && count($hadith->hints) > 0)
                <section class="reading-section lessons-section" aria-labelledby="label-lessons">
                    <div class="section-label-row">
                        <span id="label-lessons" class="section-label">MAFUNDISHO NA FAIDA ZA HADITHI</span>
                        <span class="section-script-badge">Faida {{ count($hadith->hints) }}</span>
                    </div>
                    <div class="lessons-deck">
                        @foreach($hadith->hints as $index => $hint)
                            @if(trim((string)$hint) !== '')
                                <article class="lesson-card-item">
                                    <span class="lesson-num-badge" aria-hidden="true">{{ $loop->iteration }}</span>
                                    <div class="lesson-content">
                                        <p class="lesson-text">{{ trim((string)$hint) }}</p>
                                    </div>
                                </article>
                            @endif
                        @endforeach
                    </div>
                </section>
            @endif

            {{-- 6. Rejea na Daraja la Hadithi --}}
            <div class="source-box dua-ref-box hadith-source-box">
                <div class="source-header">
                    <h2 class="source-title">Rejea na Daraja ya Hadithi</h2>
                </div>
                <div class="source-item">
                    <dt>Kitabu na Mlango:</dt>
                    <dd>{{ $hadith->chapter->book->collection->name }} · Hadith Na. {{ $hadith->number }} ({{ $hadith->chapter->book->title_sw }} · {{ $hadith->chapter->title_sw }})</dd>
                </div>
                <div class="source-item">
                    <dt>Daraja na Mpokezi:</dt>
                    <dd><span class="brief-grade-badge">{{ $hadith->grade ?? 'Sahihi' }}</span> · {{ $hadith->attribution ?? ('Imepokelewa na ' . $hadith->chapter->book->collection->name) }}</dd>
                </div>
                @if($hadith->reference_url)
                    <div class="source-item">
                        <dt>Rejea Rasmi:</dt>
                        <dd><a href="{{ $hadith->reference_url }}" target="_blank" rel="noopener noreferrer">Linganisha kwenye Sunnah.com ↗</a></dd>
                    </div>
                @endif
                @if($hadith->translation_source_url || $hadith->translator)
                    <div class="source-item">
                        <dt>Mfasiri na Chanzo:</dt>
                        <dd>
                            {{ $hadith->translator ?? 'Mfasiri Rasmi' }}
                            @if($hadith->translation_source_url)
                                · <a href="{{ $hadith->translation_source_url }}" target="_blank" rel="noopener noreferrer">Chanzo cha Tafsiri ↗</a>
                            @endif
                        </dd>
                    </div>
                @endif
                @if($hadith->license_url)
                    <div class="source-item">
                        <dt>Masharti ya Matumizi:</dt>
                        <dd><a href="{{ $hadith->license_url }}" target="_blank" rel="noopener noreferrer">Leseni ya Maudhui ↗</a></dd>
                    </div>
                @endif
                <span class="sr-only">{{ $hadith->reference }}</span>
            </div>
        </article>

        {{-- Hadithi Nyingine Katika Mlango Huu --}}
        @if($related->isNotEmpty())
            <section class="related-section dua-related-section">
                <div class="related-header">
                    <h2>Hadithi Nyingine Katika Mlango Huu</h2>
                </div>
                <div class="related-grid">
                    @foreach($related as $rel)
                        <a href="{{ route('hadith.show', $rel) }}" class="related-card">
                            <div class="related-card-meta">
                                <span class="related-col">{{ $hadith->chapter->book->collection->name }}</span>
                                <span class="related-no">Na. {{ $rel->number }}</span>
                            </div>
                            <h3 class="related-title">Hadith {{ $rel->number }}</h3>
                            <p class="related-excerpt">{{ \Illuminate\Support\Str::limit($rel->swahili, 100) }}</p>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif

        {{-- Urambazaji wa Chini (Next/Prev Navigation Cards) --}}
        <nav class="reader-bottom-nav dua-bottom-nav" aria-label="Urambazaji wa Hadithi">
            @if($prev)
                <a href="{{ route('hadith.show', $prev) }}" class="reader-nav-card nav-prev">
                    <span class="nav-card-icon" aria-hidden="true">←</span>
                    <div class="nav-card-content">
                        <span class="nav-card-hint nav-dir-label">HADITH ILIYOTANGULIA</span>
                        <strong class="nav-card-title nav-surah-name">Na. {{ $prev->number }}</strong>
                        <span class="nav-card-excerpt">{{ \Illuminate\Support\Str::limit($prev->swahili, 60) }}</span>
                    </div>
                </a>
            @else
                <div class="reader-nav-card nav-disabled">
                    <div class="nav-card-content">
                        <span class="nav-card-hint nav-dir-label">MWANZO WA MLANGO</span>
                        <strong class="nav-card-title nav-surah-name">Hadith ya Kwanza</strong>
                    </div>
                </div>
            @endif

            <a href="{{ route('library', ['collection' => $hadith->chapter->book->collection->slug, 'book' => $hadith->chapter->book_id, 'chapter' => $hadith->chapter_id]) }}" class="reader-nav-card nav-center" title="Rudi kwenye mlango huu">
                <span class="nav-card-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                    </svg>
                </span>
                <div class="nav-card-content">
                    <span class="nav-card-hint nav-dir-label">MLANGO</span>
                    <strong class="nav-card-title nav-surah-name">{{ \Illuminate\Support\Str::limit($hadith->chapter->title_sw, 24) }}</strong>
                </div>
            </a>

            @if($next)
                <a href="{{ route('hadith.show', $next) }}" class="reader-nav-card nav-next">
                    <div class="nav-card-content">
                        <span class="nav-card-hint nav-dir-label">HADITH INAYOFUATA</span>
                        <strong class="nav-card-title nav-surah-name">Na. {{ $next->number }}</strong>
                        <span class="nav-card-excerpt">{{ \Illuminate\Support\Str::limit($next->swahili, 60) }}</span>
                    </div>
                    <span class="nav-card-icon" aria-hidden="true">→</span>
                </a>
            @else
                <div class="reader-nav-card nav-disabled">
                    <div class="nav-card-content">
                        <span class="nav-card-hint nav-dir-label">MWISHO WA MLANGO</span>
                        <strong class="nav-card-title nav-surah-name">Hadith ya Mwisho</strong>
                    </div>
                </div>
            @endif
        </nav>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const playBtn = document.getElementById('audio-play');
            if (!playBtn) return;

            let isPlaying = false;
            const arabicEl = document.getElementById('text-ar');
            const swahiliEl = document.getElementById('text-sw');

            playBtn.addEventListener('click', () => {
                if (!('speechSynthesis' in window)) {
                    alert('Kivinjari chako hakitumii usomaji wa sauti wa moja kwa moja.');
                    return;
                }

                if (isPlaying) {
                    window.speechSynthesis.cancel();
                    isPlaying = false;
                    playBtn.querySelector('.btn-text').textContent = 'Soma kwa sauti';
                    playBtn.classList.remove('active');
                    return;
                }

                window.speechSynthesis.cancel();
                const textToRead = (arabicEl?.innerText || swahiliEl?.innerText || '').trim();
                if (!textToRead) return;

                const utterance = new SpeechSynthesisUtterance(textToRead);
                utterance.lang = 'ar-SA';
                utterance.rate = 0.95;

                utterance.onend = () => {
                    isPlaying = false;
                    playBtn.querySelector('.btn-text').textContent = 'Soma kwa sauti';
                    playBtn.classList.remove('active');
                };

                utterance.onerror = () => {
                    isPlaying = false;
                    playBtn.querySelector('.btn-text').textContent = 'Soma kwa sauti';
                    playBtn.classList.remove('active');
                };

                window.speechSynthesis.speak(utterance);
                isPlaying = true;
                playBtn.querySelector('.btn-text').textContent = 'Sitisha sauti';
                playBtn.classList.add('active');
            });
        });
    </script>
</x-layouts.app>
