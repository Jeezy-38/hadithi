<x-layouts.app :title="$surah->name_sw . ' (' . $surah->name_ar . ') · Qur\'ani Tukufu'">
    <div class="reader-shell quran-reader-shell">
        {{-- Upau wa Urambazaji wa Juu --}}
        <div class="reader-top-nav">
            <a href="{{ route('quran.index') }}" class="back-link">
                <span aria-hidden="true">←</span>
                <span>Orodha ya Sura</span>
            </a>
            <div class="reader-surah-jumper">
                @if($prev)
                    <a href="{{ route('quran.show', $prev->number) }}" class="surah-jump-btn" title="Sura Iliyotangulia: {{ $prev->name_sw }}">
                        <span>← {{ $prev->name_sw }}</span>
                    </a>
                @endif
                <span class="surah-jump-current">{{ $surah->number }}. {{ $surah->name_sw }}</span>
                @if($next)
                    <a href="{{ route('quran.show', $next->number) }}" class="surah-jump-btn" title="Sura Inayofuata: {{ $next->name_sw }}">
                        <span>{{ $next->name_sw }} →</span>
                    </a>
                @endif
            </div>
        </div>

        {{-- Kadi ya Kichwa cha Sura --}}
        <div class="quran-header-card">
            <div class="quran-header-badge-row">
                <span class="quran-header-pill">SURA YA {{ $surah->number }}</span>
                <span class="quran-header-pill {{ in_array(strtolower($surah->revelation_type), ['makki', 'makka']) ? 'pill-makki' : 'pill-madani' }}">
                    {{ in_array(strtolower($surah->revelation_type), ['makki', 'makka']) ? 'MAKKA' : 'MADINA' }}
                </span>
                <span class="quran-header-pill">{{ $surah->total_verses }} AYA</span>
                <span class="quran-header-pill">JUZ {{ $surah->juz_start }}</span>
            </div>

            <h1 class="quran-header-title-ar font-arabic" dir="rtl">{{ $surah->name_ar }}</h1>
            <h2 class="quran-header-title-sw">{{ $surah->name_sw }} · {{ $surah->name_en }}</h2>
            <p class="quran-header-meaning">Maana: "{{ $surah->translation_sw }}"</p>
        </div>

        {{-- Bismillah Frame (Inasimama Kujitegemea kwa Sura Zote isipokuwa Sura ya 9 At-Tawbah na Sura ya 1 Al-Faatiha) --}}
        @if($surah->number !== 9 && $surah->number !== 1)
            <div class="quran-bismillah-box" role="region" aria-label="Bismillah">
                <span class="bismillah-badge">BISMILLAHIR RAHMAANIR RAHIIM</span>
                <div class="bismillah-ar font-arabic" dir="rtl">بِسْمِ ٱللَّهِ ٱلرَّحْمَٰنِ ٱلرَّحِيمِ</div>
                <div class="bismillah-sw">Kwa jina la Mwenyezi Mungu, Mwingi wa Rehema, Mwenye Kurehemu</div>
            </div>
        @endif

        {{-- Upau wa Udhibiti wa Msomaji (Reader Toolbar) --}}
        <div class="quran-controls-bar">


            {{-- Mifumo ya Kusoma --}}
            <div class="control-group">
                <span class="control-group-label">Muonekano:</span>
                <div class="display-mode-buttons">
                    <button type="button" class="display-mode-btn active" data-mode="both">Yote</button>
                    <button type="button" class="display-mode-btn" data-mode="ar">Kiarabu</button>
                    <button type="button" class="display-mode-btn" data-mode="sw">Kiswahili</button>
                </div>
            </div>

            {{-- Kicheza Sauti ya Sura --}}
            <div class="control-group audio-controls-group">
                <button type="button" id="quran-play-all-btn" class="quran-play-all-btn">
                    <svg id="play-all-icon" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <polygon points="5 3 19 12 5 21 5 3"></polygon>
                    </svg>
                    <span id="play-all-label">Sikiliza Sura Yote</span>
                </button>
                <span class="audio-reciter-note">Msomaji: Mishary Rashid Alafasy</span>
            </div>
        </div>

        {{-- Orodha ya Aya --}}
        <div class="quran-ayahs-container" id="quran-ayahs-container">
            @forelse($ayahs as $ayah)
                <article class="quran-ayah-card" id="ayah-{{ $ayah->verse_number }}" data-verse="{{ $ayah->verse_number }}" data-audio="{{ $ayah->audio_url }}">
                    <div class="ayah-card-top-bar">
                        <div class="ayah-number-badge">
                            <span class="ayah-num-val">{{ $ayah->verse_number }}</span>
                        </div>

                        <div class="ayah-actions-bar">
                            @if($ayah->audio_url)
                                <button type="button" class="ayah-action-btn play-ayah-btn" data-audio="{{ $ayah->audio_url }}" data-verse="{{ $ayah->verse_number }}" title="Sikiliza aya hii">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor">
                                        <polygon points="5 3 19 12 5 21 5 3"></polygon>
                                    </svg>
                                    <span class="sr-only">Sikiliza</span>
                                </button>
                            @endif

                            <button type="button" class="ayah-action-btn copy-ayah-btn" data-copy="{{ $ayah->arabic_text }}&#10;&#10;{{ $ayah->translation_sw }} (Qur'an {{ $surah->number }}:{{ $ayah->verse_number }})" title="Nakili aya hii">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                                    <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                                </svg>
                                <span class="sr-only">Nakili</span>
                            </button>
                        </div>
                    </div>

                    <div class="ayah-arabic-wrap font-arabic" dir="rtl">
                        <p class="ayah-arabic-text">{{ $ayah->arabic_text }}</p>
                    </div>

                    <div class="ayah-translation-wrap">
                        <p class="ayah-translation-sw">{{ $ayah->translation_sw }}</p>
                        @if($ayah->translation_en)
                            <p class="ayah-translation-en">{{ $ayah->translation_en }}</p>
                        @endif
                    </div>
                </article>
            @empty
                <div class="empty-state">
                    <p class="empty-title">Aya za Sura Hii Zinapakuliwa...</p>
                    <p class="empty-desc">Tafadhali subiri kidogo wakati mfumo unapoweka aya za Sura hii kutoka kwenye maktaba au mtandao.</p>
                </div>
            @endforelse
        </div>

        {{-- Urambazaji wa Chini --}}
        <div class="reader-bottom-nav">
            @if($prev)
                <a href="{{ route('quran.show', $prev->number) }}" class="reader-nav-card nav-prev">
                    <span class="nav-dir-label">← SURA ILIYOTANGULIA</span>
                    <strong class="nav-surah-name">{{ $prev->name_sw }}</strong>
                    <span class="nav-surah-ar font-arabic" dir="rtl">{{ $prev->name_ar }}</span>
                </a>
            @else
                <div></div>
            @endif

            <a href="{{ route('quran.index') }}" class="reader-nav-card nav-center">
                <span class="nav-dir-label">MAKTABA</span>
                <strong class="nav-surah-name">Sura Zote 114</strong>
            </a>

            @if($next)
                <a href="{{ route('quran.show', $next->number) }}" class="reader-nav-card nav-next">
                    <span class="nav-dir-label">SURA INAYOFUATA →</span>
                    <strong class="nav-surah-name">{{ $next->name_sw }}</strong>
                    <span class="nav-surah-ar font-arabic" dir="rtl">{{ $next->name_ar }}</span>
                </a>
            @else
                <div></div>
            @endif
        </div>
    </div>

    {{-- Kicheza Sauti Kinachofanya Kazi Chini (Global Quran Audio Player Logic) --}}
    <audio id="quran-audio-player" preload="none"></audio>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const container = document.getElementById('quran-ayahs-container');
            const audioPlayer = document.getElementById('quran-audio-player');
            const playAllBtn = document.getElementById('quran-play-all-btn');
            const playAllLabel = document.getElementById('play-all-label');
            const playAllIcon = document.getElementById('play-all-icon');

            // Display Modes (both, ar, sw)
            const modeButtons = document.querySelectorAll('.display-mode-btn');
            modeButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    modeButtons.forEach(b => b.classList.remove('active'));
                    btn.classList.add('active');
                    const mode = btn.dataset.mode;
                    if (container) {
                        container.classList.remove('mode-ar-only', 'mode-sw-only');
                        if (mode === 'ar') container.classList.add('mode-ar-only');
                        if (mode === 'sw') container.classList.add('mode-sw-only');
                    }
                });
            });

            // 3. Copy Ayah
            document.querySelectorAll('.copy-ayah-btn').forEach(btn => {
                btn.addEventListener('click', async () => {
                    const text = btn.dataset.copy;
                    try {
                        await navigator.clipboard.writeText(text);
                        const originalHTML = btn.innerHTML;
                        btn.innerHTML = '<span style="font-size:11px;font-weight:700;color:var(--gold)">✓</span>';
                        setTimeout(() => btn.innerHTML = originalHTML, 2000);
                    } catch (_) {}
                });
            });

            // 4. Audio Playback (Single Ayah & Play All)
            const ayahCards = Array.from(document.querySelectorAll('.quran-ayah-card'));
            let currentPlayingIndex = -1;
            let isContinuous = false;

            function clearHighlights() {
                ayahCards.forEach(c => c.classList.remove('active-audio-ayah'));
            }

            function playAyahAtIndex(index) {
                if (index < 0 || index >= ayahCards.length) {
                    stopPlayback();
                    return;
                }

                currentPlayingIndex = index;
                clearHighlights();
                const card = ayahCards[index];
                card.classList.add('active-audio-ayah');
                card.scrollIntoView({ behavior: 'smooth', block: 'center' });

                const audioUrl = card.dataset.audio;
                if (!audioUrl) {
                    if (isContinuous) playAyahAtIndex(index + 1);
                    return;
                }

                audioPlayer.src = audioUrl;
                audioPlayer.play().catch(e => {
                    console.warn('Playback error', e);
                    if (isContinuous) playAyahAtIndex(index + 1);
                });

                if (playAllLabel) playAllLabel.textContent = `Inasoma Aya ${card.dataset.verse}... (Sitisha)`;
                if (playAllBtn) playAllBtn.classList.add('is-playing');
            }

            function stopPlayback() {
                audioPlayer.pause();
                clearHighlights();
                currentPlayingIndex = -1;
                isContinuous = false;
                if (playAllLabel) playAllLabel.textContent = 'Sikiliza Sura Yote';
                if (playAllBtn) playAllBtn.classList.remove('is-playing');
            }

            // Play single ayah buttons
            document.querySelectorAll('.play-ayah-btn').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    const card = btn.closest('.quran-ayah-card');
                    const idx = ayahCards.indexOf(card);
                    if (currentPlayingIndex === idx && !audioPlayer.paused) {
                        stopPlayback();
                    } else {
                        isContinuous = false;
                        playAyahAtIndex(idx);
                    }
                });
            });

            // Play all button
            playAllBtn?.addEventListener('click', () => {
                if (playAllBtn.classList.contains('is-playing')) {
                    stopPlayback();
                } else {
                    isContinuous = true;
                    playAyahAtIndex(0);
                }
            });

            // On track end
            audioPlayer.addEventListener('ended', () => {
                if (isContinuous && currentPlayingIndex + 1 < ayahCards.length) {
                    playAyahAtIndex(currentPlayingIndex + 1);
                } else {
                    stopPlayback();
                }
            });
        });
    </script>
</x-layouts.app>
