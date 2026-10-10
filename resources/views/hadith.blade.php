<x-layouts.app :title="$hadith->chapter->book->collection->name.' · '.$hadith->number">
    <div class="reader-shell hadith-reader-shell">
        <a class="back-link" href="{{ route('library', ['collection' => $hadith->chapter->book->collection->slug, 'book' => $hadith->chapter->book_id, 'chapter' => $hadith->chapter_id]) }}">
            <span class="back-arrow" aria-hidden="true">←</span>
            <span>Rudi kwenye mlango</span>
        </a>

        <div class="reader-heading">
            <span class="eyebrow"><span class="eyebrow-dot"></span> {{ $hadith->chapter->book->collection->name }}</span>
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

            {{-- Kidhibiti cha Ukubwa wa Maandishi (Font Size Scaler) --}}
            <div class="font-scaler-widget" aria-label="Rekebisha ukubwa wa maandishi">
                <button type="button" id="font-decrease" class="scaler-btn" title="Punguza ukubwa wa maandishi" aria-label="Punguza ukubwa">A-</button>
                <span id="font-scale-display" class="scaler-label" title="Ukubwa wa sasa">100%</span>
                <button type="button" id="font-increase" class="scaler-btn" title="Ongeza ukubwa wa maandishi" aria-label="Ongeza ukubwa">A+</button>
            </div>
        </div>

        <article class="reader-card">
            <section class="audio-player no-print" aria-label="Kusikiliza hadith" data-hadith-id="{{ $hadith->id }}" data-audio-base="{{ url('/audio/hadith/'.$hadith->id) }}"
                @if($next) data-next-url="{{ route('hadith.show', $next) }}" data-next-audio-base="{{ url('/audio/hadith/'.$next->id) }}" @endif>
                <div class="audio-header">
                    <div class="audio-header-title">
                        <span class="audio-icon" aria-hidden="true">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block; vertical-align:middle;">
                                <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon>
                                <path d="M15.54 8.46a5 5 0 0 1 0 7.07"></path>
                                <path d="M19.07 4.93a10 10 0 0 1 0 14.14"></path>
                            </svg>
                        </span>
                        <h2>Sikiliza hadith</h2>
                    </div>

                    <div class="audio-header-lang">
                        <div class="select-wrapper">
                            <select id="audio-language" aria-label="Lugha ya sauti">
                                <option value="sw">Kiswahili (Daudi)</option>
                                <option value="ar">العربية (Sheikh Shakir)</option>
                                <option value="en" @disabled(!$hadith->english)>English (Guy)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="audio-progress-container" id="audio-progress-container">
                    <div class="audio-time-row">
                        <span id="audio-current-time" class="audio-time">0:00</span>
                        <div class="audio-track-wrap">
                            <div class="audio-track-bg">
                                <div class="audio-progress-fill" id="audio-progress-fill"></div>
                            </div>
                            <div class="audio-thumb-indicator" id="audio-thumb-indicator" style="left: 0%;"></div>
                            <input type="range" id="audio-scrubber" class="audio-scrubber" min="0" max="100" value="0" step="0.1" aria-label="Muda wa sauti" disabled>
                        </div>
                        <span id="audio-total-time" class="audio-time">0:00</span>
                    </div>
                </div>

                <div class="audio-actions">
                    <button type="button" id="audio-play" class="audio-btn play-btn">
                        <span aria-hidden="true">▶</span><span class="btn-audio-label">Soma kwa sauti</span>
                    </button>
                    @if(trim((string) $hadith->arabic) !== '' && trim((string) $hadith->swahili) !== '')
                        <button type="button" id="audio-play-both" class="audio-btn both-btn" title="Soma Kiarabu kisha Kiswahili">
                            <span aria-hidden="true">▶▶</span><span class="btn-audio-label">Kiarabu + Kiswahili</span>
                        </button>
                    @endif
                    <button type="button" id="audio-pause" class="audio-btn pause-btn" disabled>
                        <span aria-hidden="true">Ⅱ</span><span class="btn-audio-label">Sitisha</span>
                    </button>
                    <button type="button" id="audio-stop" class="audio-btn stop-btn" disabled>
                        <span aria-hidden="true">■</span><span class="btn-audio-label">Acha</span>
                    </button>
                    @if($next)
                        <label class="audio-autonext" title="Hadith inayofuata: Na. {{ $next->number }}">
                            <input type="checkbox" id="audio-autonext">
                            <span>Endelea inayofuata</span>
                        </label>
                    @endif
                </div>

                <input type="hidden" id="audio-voice" value="hd-natural">
                <input type="hidden" id="audio-rate" value="1">
                <input type="hidden" id="audio-sequence" value="single">
                <audio id="hadith-native-audio" preload="metadata" style="display:none;"></audio>
                <p id="audio-status" class="audio-status-text" role="status" aria-live="polite" hidden></p>
                <noscript>Washa JavaScript ili kutumia sauti na kubadilisha lugha.</noscript>
            </section>

            <section data-reading="ar" class="reading-section arabic-section">
                <div class="section-label-bar">
                    <span class="section-label">MAANDISHI YA KIARABU · {{ $hadith->source_name }}</span>
                    <span class="arabic-lang-tag">العربية</span>
                </div>
                <div class="manuscript-box">
                    <p id="text-ar" class="arabic full-text reading-arabic" lang="ar" dir="rtl">{{ $hadith->arabic }}</p>
                </div>
            </section>

            <section data-reading="sw" class="reading-section swahili-section">
                <div class="section-label-bar">
                    <span class="section-label">TAFSIRI YA KISWAHILI</span>
                    <span class="lang-pill">Kiswahili</span>
                </div>
                <p id="text-sw" class="full-text swahili reading-swahili" lang="sw">{{ $hadith->swahili }}</p>
            </section>

            <section data-reading="en" class="reading-section english-section">
                <div class="section-label-bar">
                    <span class="section-label">ENGLISH TRANSLATION</span>
                    <span class="lang-pill">English</span>
                </div>
                @if($hadith->english)
                    <p id="text-en" class="full-text reading-english" lang="en">{{ $hadith->english }}</p>
                    <p class="source-note">Translation: <a href="https://hadeethenc.com/en/browse/hadith/{{ $hadith->source_record_id }}" target="_blank" rel="noopener noreferrer">HadeethEnc.com ↗</a></p>
                @else
                    <p class="not-available-note">Tafsiri ya Kiingereza bado haipatikani.</p>
                @endif
            </section>

            @if($hadith->explanation)
                <section class="reading-section explanation-section" aria-labelledby="label-explanation">
                    <div class="section-label-bar">
                        <span id="label-explanation" class="section-label">SHARH NA MAELEZO YA HADITHI</span>
                        <span class="lang-pill">Ufafanuzi</span>
                    </div>
                    <div class="explanation-box">
                        <p class="explanation-text">{!! nl2br(e($hadith->explanation)) !!}</p>
                    </div>
                </section>
            @endif

            @if(!empty($hadith->hints) && count($hadith->hints) > 0)
                <section class="reading-section lessons-section" aria-labelledby="label-lessons">
                    <div class="section-label-bar">
                        <span id="label-lessons" class="section-label">MAFUNDISHO NA FAIDA ZA HADITHI</span>
                        <span class="lang-pill">Mafundisho</span>
                    </div>
                    <div class="lessons-box">
                        <ul class="lessons-list">
                            @foreach($hadith->hints as $hint)
                                @if(trim((string)$hint) !== '')
                                    <li class="lesson-item">
                                        <span class="lesson-bullet" aria-hidden="true">✦</span>
                                        <span class="lesson-text">{{ trim((string)$hint) }}</span>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                </section>
            @endif

            @if($hadith->grade)
                <div class="hadith-grade-card">
                    <span class="grade-icon" aria-hidden="true">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block; vertical-align:middle;">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                            <line x1="16" y1="17" x2="8" y2="17"></line>
                        </svg>
                    </span>
                    <p class="source-note">Daraja kwa mujibu wa {{ $hadith->source_name }}: <strong>{{ $hadith->grade }}</strong> · {{ $hadith->attribution }}</p>
                </div>
            @endif
        </article>

        <section class="source-card" aria-labelledby="source-heading">
            <div class="source-card-header">
                <span class="source-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="16" x2="12" y2="12"></line>
                        <line x1="12" y1="8" x2="12.01" y2="8"></line>
                    </svg>
                </span>
                <h2 id="source-heading">Rejea na chanzo</h2>
            </div>
            <dl class="source-meta-grid">
                <div class="source-item"><dt>Rejea</dt><dd class="source-val-bold">{{ $hadith->reference }}</dd></div>
                <div class="source-item"><dt>Kitabu / mlango</dt><dd>{{ $hadith->chapter->book->number }} / {{ $hadith->chapter->number }}</dd></div>
                <div class="source-item"><dt>Mfumo wa namba</dt><dd>{{ $hadith->numbering_system }}</dd></div>
                @if($hadith->reference_url)
                    <div class="source-item"><dt>Linganisha rejea</dt><dd><a href="{{ $hadith->reference_url }}" target="_blank" rel="noopener noreferrer">{{ $hadith->chapter->book->collection->name }} {{ $hadith->number }} · Sunnah.com ↗</a></dd></div>
                @endif
                <div class="source-item"><dt>Chanzo cha Kiarabu</dt><dd><a href="{{ $hadith->source_url }}" target="_blank" rel="noopener noreferrer">{{ $hadith->source_name }}{{ $hadith->source_record_id ? ' #'.$hadith->source_record_id : '' }} ↗</a></dd></div>
                <div class="source-item"><dt>Tafsiri ya Kiswahili</dt><dd><a href="{{ $hadith->translation_source_url }}" target="_blank" rel="noopener noreferrer">{{ $hadith->translator }} ↗</a></dd></div>
                <div class="source-item"><dt>Ruhusa ya matumizi</dt><dd>{{ $hadith->license }} @if($hadith->license_url)<a href="{{ $hadith->license_url }}" target="_blank" rel="noopener noreferrer">Masharti ↗</a>@endif</dd></div>
                @if($hadith->source_fetched_at)
                    <div class="source-item"><dt>Ilipakuliwa</dt><dd>{{ $hadith->source_fetched_at->format('d/m/Y') }}</dd></div>
                @endif
                <div class="source-item"><dt>Ulinganisho uliorekodiwa</dt><dd>{{ $hadith->reviewed_by }} · {{ $hadith->reviewed_at->format('d/m/Y') }}</dd></div>
            </dl>
            @if($hadith->content_note)
                <p class="source-note source-note-box">{{ $hadith->content_note }}</p>
            @endif
        </section>

        @if($related->isNotEmpty())
            <section class="related-hadith no-print" aria-label="Hadith nyingine za mlango huu">
                <div class="related-header">
                    <h2>Hadith nyingine za mlango huu</h2>
                    <span class="related-count">{{ $related->count() }} hadith</span>
                </div>
                <ul class="related-list">
                    @foreach($related as $item)
                        <li class="related-item">
                            <a href="{{ route('hadith.show', $item) }}" class="related-link">
                                <span class="related-num">Na. {{ $item->number }}</span>
                                <span class="related-text">{{ \Illuminate\Support\Str::limit($item->swahili, 90) }}</span>
                                <span class="related-arrow" aria-hidden="true">→</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </section>
        @endif

        {{-- Urambazaji wa Chini (Next/Prev Navigation Cards) --}}
        <nav class="reader-bottom-nav hadith-bottom-nav" aria-label="Urambazaji wa Hadithi">
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
                        <span class="nav-card-hint nav-dir-label">MWANZO WA KITABU</span>
                        <strong class="nav-card-title nav-surah-name">Hadith ya Kwanza</strong>
                    </div>
                </div>
            @endif

            <a href="{{ route('library', ['collection' => $hadith->chapter->book->collection->slug, 'book' => $hadith->chapter->book_id, 'chapter' => $hadith->chapter_id]) }}" class="reader-nav-card nav-center" title="Rudi kwenye mlango wa hadith">
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
                        <span class="nav-card-hint nav-dir-label">MWISHO WA KITABU</span>
                        <strong class="nav-card-title nav-surah-name">Hadith ya Mwisho</strong>
                    </div>
                </div>
            @endif
        </nav>
    </div>
</x-layouts.app>
