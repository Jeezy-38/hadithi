<x-layouts.app :title="$hadith->chapter->book->collection->name.' · '.$hadith->number">
    <div class="reader-shell hadith-reader-shell dua-detail-shell hadith-detail-shell">
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
            $shareFullText = $shareFormatted . "\n\nSoma zaidi: " . route('hadith.show', $hadith);
            $whatsappUrl = 'https://api.whatsapp.com/send?text=' . rawurlencode($shareFullText);
        @endphp

        <div class="reader-top-nav">
            <a class="back-link" href="{{ route('library', ['collection' => $hadith->chapter->book->collection->slug, 'book' => $hadith->chapter->book_id, 'chapter' => $hadith->chapter_id]) }}">
                <span class="back-arrow" aria-hidden="true">←</span>
                <span>Nyuma</span>
            </a>

            <div class="reader-actions no-print">
                <div class="reader-more-menu-wrap">
                    <button type="button" id="reader-more-btn" class="reader-more-toggle" aria-expanded="false" aria-haspopup="true" title="Chaguzi zaidi">
                        <span class="more-dots" aria-hidden="true">⋯</span>
                        <span class="sr-only">Chaguzi zaidi</span>
                    </button>
                    <div id="reader-more-dropdown" class="reader-more-dropdown" hidden>
                        {{-- 1. WhatsApp Moja kwa Moja --}}
                        <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer" class="more-dropdown-item" id="reader-whatsapp-direct" title="Tuma moja kwa moja WhatsApp">
                            <span class="dropdown-item-icon whatsapp-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
                                    <path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.82 9.82 0 0 0 12.04 2m.01 1.67c2.2 0 4.26.86 5.82 2.42a8.23 8.23 0 0 1 2.41 5.83c0 4.54-3.7 8.24-8.24 8.24-1.48 0-2.93-.4-4.2-1.15l-.3-.18-3.12.82.83-3.04-.2-.31a8.19 8.19 0 0 1-1.26-4.38c0-4.54 3.7-8.24 8.24-8.24m4.52 11.66c-.25-.13-1.47-.72-1.7-.81-.23-.08-.39-.13-.56.13-.17.25-.64.81-.79.97-.14.17-.29.19-.54.06-.25-.13-1.06-.39-2.02-1.25-.75-.67-1.26-1.5-1.4-1.75-.15-.25-.02-.39.11-.51.11-.11.25-.29.38-.44.12-.14.17-.25.25-.42.08-.17.04-.31-.02-.44-.06-.13-.56-1.35-.77-1.85-.2-.49-.41-.42-.56-.43h-.48c-.17 0-.44.06-.67.31-.23.25-.88.86-.88 2.1 0 1.24.9 2.44 1.03 2.61.13.17 1.78 2.71 4.3 3.8.6.26 1.07.42 1.44.54.61.19 1.16.17 1.6.1.49-.07 1.47-.6 1.68-1.18.21-.58.21-1.07.14-1.18-.06-.1-.22-.16-.47-.28z"/>
                                </svg>
                            </span>
                            <span class="btn-text">WhatsApp</span>
                        </a>

                        {{-- 2. Nakili Matini --}}
                        <button type="button" id="reader-copy-direct" class="more-dropdown-item" title="Nakili hadithi yote">
                            <span class="dropdown-item-icon" id="reader-copy-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                                    <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                                </svg>
                            </span>
                            <span class="btn-text" id="reader-copy-label">Nakili Matini</span>
                        </button>

                        {{-- 3. Kushiriki Mitandao Mingine (Native Share) --}}
                        <button type="button" id="share-hadith" class="more-dropdown-item share-trigger-btn"
                            data-share-title="{{ $shareTitle }}"
                            data-share-badge="{{ $shareBadge }}"
                            data-share-ar="{{ $shareArabicShort }}"
                            data-share-sw="{{ $shareSwahiliShort }}"
                            data-share-ref="{{ $shareRef }}"
                            data-share-text="{{ $shareFormatted }}"
                            data-share-url="{{ route('hadith.show', $hadith) }}"
                            title="Shiriki kwenye mitandao mingine">
                            <span class="dropdown-item-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="18" cy="5" r="3"></circle>
                                    <circle cx="6" cy="12" r="3"></circle>
                                    <circle cx="18" cy="19" r="3"></circle>
                                    <line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line>
                                    <line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line>
                                </svg>
                            </span>
                            <span class="btn-text">Mitandao Mingine</span>
                        </button>

                        <div class="dropdown-divider" role="separator"></div>

                        {{-- 4. Hifadhi --}}
                        <button type="button" id="bookmark-toggle" class="more-dropdown-item" data-hadith-id="{{ $hadith->id }}" aria-pressed="false">
                            <span class="dropdown-item-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                </svg>
                            </span>
                            <span class="btn-text">Hifadhi</span>
                        </button>

                        {{-- 5. Soma kwa sauti (Iliyofichwa kwa ombi la mtumiaji) --}}
                        <button type="button" id="audio-play" class="more-dropdown-item speech-client-btn" title="Sikiliza kwa sauti ya kifaa" style="display: none !important;" aria-hidden="true">
                            <span class="dropdown-item-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon>
                                    <path d="M15.54 8.46a5 5 0 0 1 0 7.07"></path>
                                    <path d="M19.07 4.93a10 10 0 0 1 0 14.14"></path>
                                </svg>
                            </span>
                            <span class="btn-text">Soma kwa sauti</span>
                        </button>
                    </div>
                </div>

                <input type="hidden" id="reading-language" value="both">
            </div>
        </div>

        <div class="reader-heading dua-detail-heading">
            @if($hadith->title)
                <h1 class="reader-title">{{ $hadith->title }}</h1>
            @else
                <h1 class="reader-title">Hadith Na. {{ $hadith->number }}</h1>
            @endif
        </div>

        <article class="reader-card dua-detail-card hadith-detail-card">
            {{-- 1. Matini ya Kiarabu --}}
            <section class="reading-section" data-reading="ar" aria-labelledby="label-arabic">
                <div class="section-label-row">
                    <span id="label-arabic" class="section-label">MAANDISHI YA KIARABU</span>
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

            {{-- 4. Mafundisho na Faida za Hadithi (kama zipo) --}}
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

            <a href="{{ route('library', ['collection' => $hadith->chapter->book->collection->slug, 'book' => $hadith->chapter->book_id, 'chapter' => $hadith->chapter_id]) }}" class="reader-nav-card nav-center" title="Nyuma">
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

            // Reader More Actions Menu Dropdown
            const moreBtn = document.getElementById('reader-more-btn');
            const moreDropdown = document.getElementById('reader-more-dropdown');
            if (moreBtn && moreDropdown) {
                moreBtn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    const isHidden = moreDropdown.hasAttribute('hidden');
                    if (isHidden) {
                        moreDropdown.removeAttribute('hidden');
                        moreBtn.setAttribute('aria-expanded', 'true');
                    } else {
                        moreDropdown.setAttribute('hidden', '');
                        moreBtn.setAttribute('aria-expanded', 'false');
                    }
                });
                document.addEventListener('click', function (e) {
                    if (!moreDropdown.contains(e.target) && e.target !== moreBtn) {
                        moreDropdown.setAttribute('hidden', '');
                        moreBtn.setAttribute('aria-expanded', 'false');
                    }
                });
            }

            // Direct Copy Button in Dropdown
            const copyDirectBtn = document.getElementById('reader-copy-direct');
            if (copyDirectBtn) {
                copyDirectBtn.addEventListener('click', async function (e) {
                    e.stopPropagation();
                    const shareBtn = document.getElementById('share-hadith');
                    const textToCopy = shareBtn ? (shareBtn.dataset.shareText + '\n\nSoma zaidi: ' + shareBtn.dataset.shareUrl) : window.location.href;
                    try {
                        await navigator.clipboard.writeText(textToCopy);
                        const label = document.getElementById('reader-copy-label');
                        const icon = document.getElementById('reader-copy-icon');
                        const origLabel = label ? label.textContent : '';
                        const origIcon = icon ? icon.innerHTML : '';
                        if (label) label.textContent = '✓ Imenakiliwa!';
                        if (icon) icon.innerHTML = '<span style="color:var(--gold);font-weight:700;">✓</span>';
                        setTimeout(() => {
                            if (label) label.textContent = origLabel || 'Nakili Matini';
                            if (icon) icon.innerHTML = origIcon;
                        }, 2200);
                    } catch (_) {}
                });
            }

            // Direct Native Share in Dropdown
            const nativeShareBtn = document.getElementById('share-hadith');
            if (nativeShareBtn) {
                nativeShareBtn.addEventListener('click', async function (e) {
                    if (navigator.share) {
                        e.preventDefault();
                        e.stopPropagation();
                        try {
                            await navigator.share({
                                title: this.dataset.shareTitle || document.title,
                                text: `${this.dataset.shareText}\n\nSoma zaidi: ${this.dataset.shareUrl}`,
                                url: this.dataset.shareUrl
                            });
                            if (moreDropdown) {
                                moreDropdown.setAttribute('hidden', '');
                                moreBtn.setAttribute('aria-expanded', 'false');
                            }
                        } catch (_) {}
                    }
                });
            }
        });
    </script>
</x-layouts.app>
